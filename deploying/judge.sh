#!/bin/bash
#cd ../
#共享文件列表
file=(
config/db.php
config/dbOA.php
config/db_dc.php
)
#共享目录列表
dir=(
runtime
web/uploads
web/temp
)
sharedir=$1
echo ${sharedir}

for i in ${file[*]}
do
#  echo $i 
 if [[ ! -f "$i" || -f "$i" ]]; then
   rm -rf $i
   ln -s $sharedir$i $i
   echo $i
 fi  
done
echo 
for a in  ${dir[@]}
do
 if [[ ! -f "$a" ||  -f "$a" ]]; then
   rm -rf $a
   ln -s $sharedir$a $a
    echo $a
 fi
done