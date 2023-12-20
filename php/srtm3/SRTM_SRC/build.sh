#!/bin/sh

svn co http://www.nsl.tuis.ac.jp/svn/linux/JunkBox_Lib/trunk JunkBox_Lib
cd JunkBox_Lib
./config.sh
./configure
make
cd ..

make

