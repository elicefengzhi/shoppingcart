插入和更新时模块调用
-----------
插入
$this->serviceLocator->get('FormSubmit')->Insert();

更新
$this->serviceLocator->get('FormSubmit')->Update();

以上都会返回一个布尔值代表是否存在需要操作的数据(默认为post)，也可自行判断

可调用方法
-----------
setRequestType：
此方法接收一个字符串，设置接收的数据类型，可以接收get、post和cookie，默认为post

$property = $this->serviceLocator->get('FormSubmit');
$property->setRequestType('get');

IsRequestReturnFalse:
此方法接收一个布尔值，设置通过'RequestType'所给定的类型获得'requestData'的值如果是空的话是否返回'false'(默认为true)

requestData:
此方法接收一个数组，需要操作的数据，默认情况下操作数据是自动获取，不需要额外传参

$insert = $this->serviceLocator->get('FormSubmit')->Insert();
$insert->requestData(array('name' => 'test','age' => 12));

table:
此方法接收一个字符串或一个对象，执行操作的表名或数据操作对象
如果参数是对象，下列方法所给出的待调用方法名都会从此对象中调用
'dbInsertFunction'、'insertExistsFunction'、'dbUpdateFunction'、'updateExistsFunction'

$this->serviceLocator->get('FormSubmit')->Insert()->table('user');

执行插入操作，操作的数据表是'user'

$this->serviceLocator->get('FormSubmit')->Insert()->table($this->serviceLocator->get('DbSql')->User());

此处参数是一个对象，如果module.conifg.php中已经定义了'dbInsertFunction'，在执行插入时会自动调用该方法
如果没有定义'dbInsertFunction'，则需要为'dbInsertFunction'函数赋值
update操作需要注意事项与insert相同

where:
此方法接收boolean|Where|\Closure|string|array，数据库操作所需要的条件

$this->serviceLocator->get('FormSubmit')->Update()->table('user')->where(array('name' => 'test'));

或

$where = new \Zend\Db\Sql\Where();
$where = $where->equalTo('name', 'test');
$this->serviceLocator->get('FormSubmit')->Update()->table('user')->where($where);

执行更新操作，操作的数据表是'user'，条件是'name'等于'test'
如果table为一个自定义的对象，对where赋值是无效的

existsFields:
此方法接收一个数组，数据库插入和更新时往往需要验证当前需要操作的数据字段是否已经在数据库里存在

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->existsFields(array('name'));

'user'表的'name'字段值不能重复

existsWhere:
此方法接收boolean|Where|\Closure|string|array，存在验证附加的条件，使用方法参照'where'

validate:
此方法接收一个自定义的对象或布尔值，自定义验证的对象，当是'false'时表示不验证
如果同时为'validate'和'inputfilter'赋值，以inputfilter优先(先执行)

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->validate($this->serviceLocator->get('Validate')->User())

'user'表使用'$this->serviceLocator->get('Validate')->User()'对象进行自定义验证

validateFunction:
第一个参数是调用的方法名，后面的参数任意，为需要传给此方法的参数

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->validate($this->serviceLocator->get('Validate')->User())
->validateFunction('functionName','param1','param2')

validateErrorMessageFunction:
此方法接收一个字符串，自定义验证时自定义验证对象通过此方法给出可供调用的错误信息
如果不给此函数传参，则使用配置文件中的'errorMessageFunction'的值调用

validatedData:
此方法接收一个数组，设置验证后数据，多用于触发事件时动态修改验证后数据

sourceValidateErrorMessage:
此方法接收一个字符串，设置验证错误提示信息
如果不给此函数传参，则使用ErrorMessage.php中的值，详细可见ErrorMessage.php
数组键名：
'maxSizeError' 媒体上传最大容量
'minSizeError' 媒体上传最小容量
'mimeTypeError' 媒体上传mime类型
'existsError' 数据存在

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->sourceValidateErrorMessage(array('maxSizeError' => '上传最大限制为%s'))

inputFilter:
此方法接收一个数组，传递一个给'Zend\InputFilter\Factory'对象'createInputFilter'方法的配置参数，用于验证、过滤
如果同时为'validate'和'inputfilter'赋值，以inputfilter优先(先执行)

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->inputFilter(array(....))

form:
第一个参数为'\Zend\Form\Form'对象或一个数组，如果是对象就使用传入的对象，如果是数组尝试使用数组创建一个'\Zend\Form\Form'。
第二个参数为一个数组，'form'的属性设置('setAttributes')

dbInsertFunction:
第一个参数是调用的方法名，后面的参数任意，为需要传给此方法的参数，插入数据库时需要调用的方法名及相关参数
待调用的函数需要返回一个布尔值以代表是否验证通过(程序会强转返回值为布尔类型)
如果给'table'传递了对象，就会调用此方法名的方法，此时这个方法必须接收一个参数用于获得经过处理有的'requestData'(比如下面方法'add'的$data参数)。
如果不给此函数传参，则使用自带的程序执行插入操作

function add($data) {
	...
}

如果给'table'传递了对象但是没有显示调用此方法，程序会根据模块配置文件中'dbInsertFunction'定义的方法名传参

$this->serviceLocator->get('FormSubmit')->Insert()->table($this->serviceLocator->get('DbSql')->User())->dbInsertFunction('functionName')

isCustomExists:
此方法接收一个布尔值，是否进行自定义存在验证(默认不进行)

insertExistsFunction:
第一个参数是调用的方法名，后面的参数任意，为需要传给此方法的参数，插入数据库时存在验证需要调用的方法名及相关参数
如果对此函数传参，则自动执行自定义插入存在验证(isCustomExists = true)
待调用的函数需要返回一个布尔值以代表是否验证通过(程序会强转返回值为布尔类型)
此方法调用'table'方法传入的对象，这个方法第一个一个参数必须用于获得'existsFields'方法传递的参数(比如下面方法'insertExistsFunction'的$existsField参数)，第二个参数必须获得'existsWhere'方法传递的参数(比如下面方法'insertExistsFunction'的$existsWhere参数)。
如果不给此函数传参，则使用自带的程序执行更新操作

function insertExistsFunction($existsField,$existsWhere) {
	...
}

如果给'table'传递了对象但是没有显示调用此方法，程序会根据模块配置文件中'insertExistsFunction'定义的方法名传参

$this->serviceLocator->get('FormSubmit')->Insert()->table($this->serviceLocator->get('DbSql')->User())->existsFields(array('name'))->insertExistsFunction('functionName')

dbUpdateFunction:
第一个参数是调用的方法名，后面的参数任意，为需要传给此方法的参数，更新数据库时需要调用的方法名及相关参数
待调用的函数需要返回一个布尔值以代表是否验证通过(程序会强转返回值为布尔类型)
如果给'table'传递了对象，就会调用此方法名的方法，此时这个方法第一个参数用于获得经过处理有的'requestData'(比如下面方法'update'的$data参数)，第二个参数必须接受'where'方法传递的值(比如下面方法'update'的$where参数)。
如果不给此函数传参，则使用自带的程序执行更操作

function dbUpdateFunction($existsField,$existsWhere) {
	...
}

如果给'table'传递了对象但是没有显示调用此方法，程序会根据模块配置文件中'dbUpdateFunction'定义的方法名传参

$this->serviceLocator->get('FormSubmit')->Update()->table($this->serviceLocator->get('DbSql')->User())->dbUpdateFunction('functionName')

updateExistsFunction:
参见'insertExistsFunction'

isFilter:
此方法接收一个布尔值，是否过滤requestData(默认进行stringTrim+stripTags+htmlEntities+stripNewLines验证)

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->isFilter(false);

customFilter:
此方法接收一个数组，自定义过滤
关于自定义过滤:
1. 为"null"，则视为多余字段从request参数中注销
2. 不过滤 为 0
3. STRINGTRIM 为 1
4. STRIPTAGS 为 2
5. HTMLENTITIES 为 4
6. STRIPNEWLINES 为 8
过滤形式(数组键值为字段名，值为过滤值):
1. array('filter1' => 15 , 'filter2' => 2)
2. array('filter1' => 1 + 2 + 4 + 8 , 'filter2' => 4 + 8)
3. array('filter1' => \FormSubmit\Filter\Filter::HTMLENTITIES + \FormSubmit\Filter\Filter::STRINGTRIM)

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->customFilter(array('filter1' => null,'filter2' => 0,'filter3' => 1 + 4 + 8));

addField:
此方法接收一个数组，设置附加字段。附加字段会在requestData验证和过滤后合并入requestData

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->addField(array('create_time' => time()));

isTransaction:
此方法接收一个布尔值，是否进行事物(默认不进行)

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->isTransaction(true)

mediaUpload:
此方法接收两个参数，媒体上传
第一个参数为对象或者布尔值，如果是对象则使用此对象，如果是'false'使用程序自带对象(默认为'false')
第二个参数为布尔值，媒体上传后的地址是否合并入验证后requestData(默认为'true')

helper:
如果有一些相同扩展操作在多出使用，可以定义在'helper'中
'helper'如果继承自'BaseHelper'，则可以通过成员变量'formSubmit'获取'FormSubmit'类的对象
'helper'可以通过实现'registerFunction'方法，注册一个可在'FormSubmit'类中'getHelperFunction'方法调用的方法名
第一个参数：事件名(ValidateBefore、ValidateAfter、InputFilterBefore、InputFilterAfter、ExistsBefore、ExistsAfter、DbBefore、DbAfter)
第二个参数：帮助类的类名
之后的参数任意，这些参数都会赋值给相应帮助类的'init'方法
帮助类需要定义在'Helper'文件夹中，帮助类必须公开实现'init'和'action'方法及继承'BaseHelper'类。具体可参照'ChildColumns'

getHelperFunction:
执行相应helper对象方法，第一个参数为'helper'对象名('helper'方法的第二个参数)，'helper'方法名，后续参数为此方法需要传入的参数

$target->getHelperFunction('ChildColumns','getChildColumnsValues','TypeProduct')

调用'ChildColumns'帮助类的'getChildColumnsValues'方法，对此方法传一个参数'TypeProduct'

isRollBack:
此方法接收一个布尔值，是否回滚(默认不进行)
只有在'isTransaction'为'true'时，此方法设置'true'，才会有效果

isVal:
此方法接收一个布尔值，是否验证通过(默认不通过)

isExists:
此方法接收一个布尔值，是否数据存在验证通过

getServiceLocator:
获得serviceLocator

getValidateErrorMessage:
获得验证错误信息

getValidatedData:
获得验证及过滤后requestData

getUploadedPath:
获得所有媒体上传路径

getSourceData:
获得原始数据

getLastInsertId:
获得最后插入id(在执行数据库插入时，程序会自动获得此值。如果是自定义插入，程序会尝试调用对象的'lastInsertId'方法来获得此值)

getOtherErrorMessage:
获得其它错误信息
