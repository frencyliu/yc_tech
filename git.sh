#/bin/bash

git add .
git commit -m 'readd pageparentdiv metabox'

git push origin DEV

git checkout master

git merge DEV

git push origin master

git checkout DEV
