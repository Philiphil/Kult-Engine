<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Kult Engine
 * @author Théo Sorriaux (philiphil)
 * @copyright Copyright (c) 2016, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

include '../config.php';
use KultEngine as k;

k\Invoker::requireBase(['Router']);
k\page::standardpage_head();
k\page::standardpage_header();
k\page::standardpage_body_begin();

echo k\text::get_text('hello');
?>
<script>
	var req = new ReqAjax("test");
	req.send("/api/demo.ajax.php", function(call){
		console.debug(call);
	})
</script>
<?php

$d = new k\DaoGenerator(new pokemon(), new k\Connector());
$d->create_table();


trait TimedTrait
{
	public ?\DateTime $createdAt=null;
	public ?\DateTime $modifiedAt=null;
	public ?\DateTime $deletedAt=null;

}

class user extends k\DaoableObject
{
	use TimedTrait;
	public string $email="string";

}

class userPassword extends k\DaoableObject{
	public string $password="string";
	public int $userId=0;
}

class userSocialAccount extends k\DaoableObject
{
	public string $uid="string";
	public int $platform=0;
	public int $userId=0;

	const PLATFORM_TYPE_FACEBOOK=1;
	const PLATFORM_TYPE_GOOGLE=2;
	const PLATFORM_TYPE_GITHUB=3;
	const PLATFORM_TYPE_TWITTER=4;
}

$d = new k\DaoGenerator(new user(), new k\Connector());
$d->create_table();

$d = new k\DaoGenerator(new userPassword(), new k\Connector());
$d->create_table();

$d(new userSocialAccount());
$d->create_table();

k\page::standardpage_body_end();
k\page::standardpage_footer();
