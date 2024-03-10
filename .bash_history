mkdir .ssh
ls
cd .ssh/
ls
vi authorized_keys
chmod 400 authorized_keys 
pwd
cd ..
ls
git init
git add README.md
echo "# easy-installer" >> README.md
git add README.md
git commit -m "first commit"
git branch -M master
git remote add origin git@github.com:HostingBE/easy-installer.git
git push -u origin master
ls
cd .ssh/
vi id_rsa
ls -altr
chmod 400 id_rsa 
cd ..
git push -u origin master
exit
git add .
git commit -m "changed the readme file"
ls -altr
exit
