use scan_dir::ScanDir;
use std::{
    ffi::OsStr,
    fs::{self, create_dir_all},
    io::Read,
    vec,
};

use console::{style, Emoji};
use indicatif::{ProgressBar, ProgressStyle};

use clap::Parser;

static LOOKING_GLASS: Emoji<'_, '_> = Emoji("🔍  ", "");

#[derive(Parser)]
#[command(author, version, about, long_about = None)]
pub struct Args {
    /// Decode php execute file
    #[arg(short, long, value_name = "FILE")]
    pub exe: std::path::PathBuf,

    /// To  be decryption folder
    #[arg(short, long, value_name = "FOLDER")]
    pub input: std::path::PathBuf,

    /// Save the decrypted file directory
    #[arg(short, long, value_name = "FOLDER")]
    pub output: std::path::PathBuf,
}

pub fn check_exist(arg: &Args) -> Option<String> {
    if !arg.exe.exists() {
        return Some(arg.exe.as_path().display().to_string());
    }

    if !arg.input.exists() {
        return Some(arg.input.as_path().display().to_string());
    }

    // Create folder if output folder not exists
    if !arg.output.exists() {
        std::fs::create_dir_all(&arg.output).unwrap_or_else(|e| panic!("Error create dir: {}", e));
    }

    None
}

/// Scan and find Zend Guard php file
pub fn scan_folder(path: &std::path::PathBuf) -> Vec<std::path::PathBuf> {
    let mut files: Vec<std::path::PathBuf> = vec![];

    let mut dirs = vec![path.clone()];
    while dirs.len() > 0 {
        let path = dirs.pop().unwrap();

        ScanDir::all()
            .read(path, |iter| {
                for (entry, _) in iter {
                    let path = entry.path();
                    if path.is_dir() {
                        dirs.push(path);
                    } else {
                        files.push(path);
                    }
                }
            })
            .unwrap();
    }
    return files;
}

pub fn is_zended(path: &std::path::PathBuf) -> bool {
    if !path.exists() {
        return false;
    }

    match path.extension().and_then(OsStr::to_str) {
        Some(name) => {
            if name.to_lowercase() != "php" {
                return false;
            }
        }
        None => {
            return false;
        }
    }

    // read up to 4 bytes
    let mut buffer = vec![0; 4];
    let mut file = std::fs::File::open(path).unwrap();
    match file.read(&mut buffer[..]) {
        Ok(_) => match std::str::from_utf8(&buffer) {
            Ok(s) => {
                if s.to_lowercase() == "zend" {
                    return true;
                }
            }
            Err(_) => {}
        },
        Err(_) => {}
    };
    return false;
}

///
pub fn replace_path(
    file: &std::path::PathBuf,
    src: &std::path::PathBuf,
    dst: &std::path::PathBuf,
) -> std::path::PathBuf {
    let relative = file.strip_prefix(src).expect("Not a prefix");
    let result = dst.join(relative);

    let result_folder = result.parent().unwrap();

    if !result_folder.exists() {
        create_dir_all(result_folder).unwrap();
    }
    // if result.exists() {
    //     match remove_file(&result) {
    //         Ok(_) => {}
    //         Err(_) => {
    //             return None;
    //         }
    //     }
    // }
    result
}

/// copy file to destion directory
pub fn copy_file_to_other(
    file: &std::path::PathBuf,
    src: &std::path::PathBuf,
    dst: &std::path::PathBuf,
) {
    let result = replace_path(file, src, dst);

    // println!("Copy {} to {}",file.display().to_string(),result.display().to_string());
    fs::copy(file, result).unwrap();
}

/// Decrypting php files
pub fn decode(file_path: &std::path::PathBuf, args: &Args) {
    let exe_file = args.exe.display().to_string();
    let target_file = replace_path(file_path, &args.input, &args.output);

    match std::process::Command::new(exe_file)
        .args([
            file_path.display().to_string(),
            target_file.display().to_string(),
        ])
        .spawn()
    {
        Ok(_) => {
            // println!("status: {}", output.status);
            // println!("stdout: {}", String::from_utf8_lossy(&output.stdout));
            // println!("stderr: {}", String::from_utf8_lossy(&output.stderr));
        }
        Err(_) => {
            // println!("Error: {}",err.to_string());
        }
    }
}

fn main() {
    // check argument
    let args = Args::parse();
    match check_exist(&args) {
        Some(path) => {
            panic!("Not find so such or directory {}", path)
        }
        None => {}
    }

    // Init process bar
    let spinner_style = ProgressStyle::with_template("{prefix:.bold.dim} {spinner} {wide_msg}")
        .unwrap()
        .tick_chars("⠁⠂⠄⡀⢀⠠⠐⠈ ");

    println!(
        "{} {}Scan folder [{}]",
        style("[1/2]").bold().dim(),
        LOOKING_GLASS,
        args.input.display().to_string(),
    );

    let files = scan_folder(&args.input);
    let numbers = files.len();

    let pb = ProgressBar::new(numbers as u64);
    pb.set_style(spinner_style.clone());
    pb.set_prefix(format!("[2/2]Decode: "));
    // Scan folders and find Zend encrypted files
    for file_path in files {
        // println!(
        //     "scan file {} and it is zend encryption: {} ",
        //     file_path.display().to_string(),
        //     is_zended(&file_path)
        // );

        if is_zended(&file_path) {
            decode(&file_path, &args);
            pb.set_message(format!("{}", file_path.display().to_string()));
        } else {
            copy_file_to_other(&file_path, &args.input, &args.output);
        }
        pb.inc(1);
    }
    pb.finish_and_clear();
}
