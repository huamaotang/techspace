# git学习笔记
## 基础命令
把指定目录变成Git可以管理的仓库

```
git init
```
克隆远程仓库

```
git clone git@github.com:huamaotang/techspace.git
```
掌握仓库当前的状态

```
git status
```
能看看具体修改了什么内容

```
git diff
```
把文件修改添加到暂存区

```
git add
```
把暂存区的所有内容提交到当前分支

```
git commit -m  
```
显示从最近到最远的提交日志

```
日志简要
git log --pretty=oneline
可以看到分支合并图
git log --graph     
可以看到分支的合并情况
git log --graph --pretty=oneline --abbrev-commit
```
HEAD表示当前版本上一个版本就是HEAD^，上上一个版本就是HEAD^^，当然往上100个版本写100个^比较容易数不过来，所以写成HEAD~100

```
git reset --hard HEAD^
```
可以指定回到未来的某个版本

```
git reset --hard 3628164
```
用来记录你的每一次命令

```
git reflog
```
可以丢弃工作区的修改

```
git checkout -- readme.txt
```
查看远程分支

```
git remote -v
```
查看本地分支

```
git branch -a
```
提交到远程分支

```
git push -u origin_github master     （第一次提交）
git push origin_github master
```
创建dev分支，然后切换到dev分支

```
git checkout -b dev
git branch dev
git checkout dev
```
把dev分支的工作成果合并到master分支上

```
git merge dev
git merge --no-ff master //no fast-forward
```
删除dev分支

```
git branch -d dev
```
## 配置
查看配置信息

```
git config --list
```
别名配置

```
git config --global alias.df diff
git config --global alias.st status
git config --global alias.ci commit
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.lg "log --color --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit"
git config --global alias.cf config
```
区分文件名大小写

```
git config --global core.ignorecase false //区分
```
设置pull默认为rebase

```
git config --global pull.rebase=true
```
设置用户名和邮箱

```
git config --global user.name Tom
git config --global user.email tanghuamao@noahwm.com
```
