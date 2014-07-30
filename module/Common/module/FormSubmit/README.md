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
