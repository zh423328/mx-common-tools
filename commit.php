<?php

$tag = 'v1.0.1';
$msg = '进行细微的调整,添加了curl post/get';

echo `git add .`;
echo `git commit -m "$msg"`;
echo `git tag $tag`;
echo `git push origin master`;
echo `git push origin --tags`;