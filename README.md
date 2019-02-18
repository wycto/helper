## PHP助手函数类库集

### 安装

~~~
composer require wycto/helper
~~~

### 使用方法

~~~
//静态调用，获取IP
HelperCommon::getip();

//静态调用，移除空值
$args = array_merge($this->_request->get(), $this->_request->post());
HelperArray::removeEmpty($args);
~~~

#### 类库列表，持续更新
~~~
HelperCommon 通用类


HelperApi: API类

HelperArray：数组类

HelperDateTime：日期类

HelperImg：图片类

HelperSpell：拼音类（汉字转拼英）

HelperString：字符串类

HelperValidate：验证器类
~~~
