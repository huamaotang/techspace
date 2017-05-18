## map & reduce

```
	'use strict';
	var data = [2,3];
	var res = data.map(function pow(x) {
		return x * 100;
	});

	console.log(data);
	console.log(res);

	var data1 = [2,3,4,5];
	var res1 = data1.reduce(function (x, y){
		return x + y * 10
	});

	console.log(res1);
