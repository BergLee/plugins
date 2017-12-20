## ueditor在选项卡切换过程中无法控制高度问题
> 这是由于ueditor默认开启了自适应高度造成的。
开启并修改`ueditor.config.js`的参数如下：

```
autoHeightEnabled:false
```

## yii2框架去掉Url中的web

### web同级目录的.htaccess:
```
Options +FollowSymLinks
IndexIgnore */*
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ web/index.php/$1 [QSA]
```
### `\yii\web\Request`修改:
```php
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }

        //return $this->_baseUrl;
        return dirname($this->_baseUrl);
    }
```
