# SeayDzend-R
---
模仿SeayDzend.exe 工具。原工具不知为何在解密Zend5.2 会报错，遂写个替换掉 SeayDzend.exe 的工具。

# 编译
```
git clone https://github.com/charSLee013/SeayDzend-R 
cd SeayDzend-R  
cargo build --release
```

# 使用
请确定php的加密方式, `\lib` 文件夹里分别对应的解密方式是

* `\lib\bin\` Zend5.2 解密
* `\lib\bin2\` Zend5.3 解密
* `\lib\bin3\` Zend55.4 解密

具体说明
```cmd
cmd> seay_dzend_r.exe -h
Usage: seay_dzend_r.exe --exe <FILE> --input <FOLDER> --output <FOLDER>

Options:
  -e, --exe <FILE>       Decode php execute file
  -i, --input <FOLDER>   To  be decryption folder
  -o, --output <FOLDER>  Save the decrypted file directory
  -h, --help             Print help information
  -V, --version          Print version information
```

## 例子
* Zend 5.2 解密
* 加密文件在 `D:\webroot\`
* 将其解密文件和其他文件保存到 `D:\web\`

```cmd
git clone https://github.com/charSLee013/SeayDzend-R
cd SeayDzend-R 
## 编译或下载二进制执行文件

.\seay_dzend_r.exe -e .\lib\bin\php.exe -i D:\webroot\ -o D:\web\
```


