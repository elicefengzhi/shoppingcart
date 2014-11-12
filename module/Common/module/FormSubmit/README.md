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

requestData:
此方法接收一个数组，需要操作的数据，默认情况下操作数据是自动获取，不需要额外传参

$insert = $this->serviceLocator->get('FormSubmit')->Insert();
$insert->requestData(array('name' => 'test','age' => 12));

table:
此方法接收一个字符串或一个对象，执行操作的表名或数据操作对象

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
如果同时为'validate'和'inputfilter'赋值，以inputfilter优先

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->validate($this->serviceLocator->get('Validate')->User())

'user'表使用'$this->serviceLocator->get('Validate')->User()'对象进行自定义验证

validateFunction:
第一个参数是调用的方法名，后面的参数任意，为需要传给此方法的参数

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->validate($this->serviceLocator->get('Validate')->User())
->validateFunction('functionName','param1','param2')

inputFilter:
此方法接收一个数组，传递一个给'Zend\InputFilter\Factory'对象'createInputFilter'方法的配置参数，用于验证、过滤
如果同时为'validate'和'inputfilter'赋值，以inputfilter优先

$this->serviceLocator->get('FormSubmit')->Insert()->table('user')->inputFilter(array(....))

dbInsertFunction:
此方法接收一个字符串，插入操作的函数名。
如果给'table'传递了对象，就会调用此方法名的方法，此时这个方法必须接收一个参数用于获得经过处理有的requestData。
如果给'table'传递了对象但是没有显示调用此方法，程序会根据模块配置文件中定义的方法名传参。

$this->serviceLocator->get('FormSubmit')->Insert()->table($this->serviceLocator->get('DbSql')->User())->dbInsertFunction('functionName')


#设置post、get提交
$property = $this->serviceLocator->get('FormSubmit');
$property->setRequestType('get');//post(默认)或get

#添加数据一般形式
//'news_title'不可重复，操作表为'News'，$this->serviceLocator->get('Validate')->AdminNews()为验证的对象。
$page->insert()->existsFields(array('news_title'))->table('News')->validate($this->serviceLocator->get('Validate')->AdminNews())->submit();

#更新数据一般形式
//当前修改的数据为'news_id'为'4'的数据，'news_title'不可重复操作表为'News'，$this->serviceLocator->get('Validate')->AdminNews()为验证的对象。
$return = $page->update()->table('News')->where(array('news_id' => 4))->existsFields(array('news_title'))->validate($this->serviceLocator->get('Validate')->AdminNews())->submit();

#关于自定义过滤
1. 不过滤 为 0
2. 为"null"，则视为多余字段从request参数中注销
3. STRINGTRIM 为 1
4. STRIPTAGS 为 2
5. HTMLENTITIES 为 4
6. STRIPNEWLINES 为 8
//过滤形式
1. array('filter1' => 15 , 'filter2' => 2)
2. array('filter1' => 1 + 2 + 4 + 8 , 'filter2' => 4 + 8)
3. array('filter1' => \FormSubmit\Filter\Filter::HTMLENTITIES + \FormSubmit\Filter\Filter::STRINGTRIM)
//在更新数据时使用自定义过滤
$return = $page->update()->table('News')->where(array('news_id' => $nId))->existsFields(array('news_title'))->customFilter(array('editorValue' => null,'news_body' => 0,'news_title' => 1 + 4 + 8))->validate($this->serviceLocator->get('Validate')->AdminNews())->submit();
