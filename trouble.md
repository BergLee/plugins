ueditor在选项卡切换过程中无法控制高度问题
---
>>> 这是由于ueditor默认开启了自适应高度造成的。
开启并修改ueditor.config.js的参数如下：

```
autoHeightEnabled:false
```
