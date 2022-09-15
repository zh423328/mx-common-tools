<?php

$tag = 'v1.0.2';
$msg = '进行细微的调整,添加了curl post/get';

echo `git add .`;
echo `git commit -m "$msg"`;
echo `git tag $tag`;
echo `git push origin main`;
echo `git push origin --tags`;