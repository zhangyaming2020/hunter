var shop_cart_param = {
	emall_shop_cart_cookie_name: "emallShopCartProducts",
	quick_shop_cart_cookie_name: "quickShopCartProducts",
	product_quantity_separator: "@",
	product_separator: ",",
	product: "productId",
	quantity:"quantity",
	price:"price",
	name:"name",
	store:"store",
	productInfos:"productInfos",
	productIds:"productIds",
	type:"type",
	totalQuantity:"totalQuantity",
	totalPrice:"totalPrice"
}
var shop_cart_options = {
	expires:60*60*24,
	path:"/"
};
var commonParam = {
	backPath : "backPath",
	productBackPath : "productBackPath",
	timestamp : "timestamp",
	longitude:"longitude",
	latitude:"latitude",
	addressLongitude:"addressLon",
	addressLatitude:"addressLat",
	address:"address",
	city:"city",
	homeAddress:"homeAddress"
}
/**
 * 购酒商城增减购物车cookie中商品的数量
 * @param cookieName			cookie名称
 * @param productId				要增加的产品ID
 * @param quantity       		要增加的产品的数量
 * @return 成功信息
 */
function buildEmallProductsCookie(cookieName,productId,quantity,userId){
	var beforeCookieStr = getWebCookie(cookieName);
	var resultNumber = quantity;
	var cartInfo = getEmallCartInfo();
	var resultString = "加入购物车成功";
	if(cartInfo != null){
		var productInfos = cartInfo[shop_cart_param.productInfos];
		if(productInfos != null && productInfos.length > 0){
			for(var i = 0;i < productInfos.length;i++){
				var product = productInfos[i][shop_cart_param.product];
				if(product == productId){
					var num = parseInt(productInfos[i][shop_cart_param.quantity]);
					if(num + parseInt(quantity) > 99){
						resultNumber = (99 - num > 0) ?  99 - num : 0;
						resultString = "单品限购99"
					}
				}
			}
		}
	}
	var newCookieStr = buildEmallProductsCookieString(beforeCookieStr,productId,resultNumber);
	addWebCookie(cookieName,newCookieStr,shop_cart_options);
	return resultString;
}
/**
 * 购酒商城删除购物车cookie中商品
 * @param cookieName			cookie名称
 * @param productIds			要删除的产品ID
 * @return String				删除后的cookie值
 */
function delCookieEmallProducts(cookieName,productIds){
	var beforeCookieStr = getWebCookie(cookieName);
	var newCookieStr = delCookieEmallProductsString(beforeCookieStr,productIds);
	addWebCookie(cookieName,newCookieStr,shop_cart_options);
	return true;
}
/**
 * 快喝删除购物车cookie中商品
 * @param cookieName			cookie名称
 * @param productIds			要删除的产品ID
 * @return String				删除后的cookie值
 */
function delCookieQuickProducts(cookieName,productIds,storeId){
	var beforeCookieStr = getWebCookie(cookieName);
	var newCookieStr = delCookieQuickProductsString(beforeCookieStr,productIds,storeId);
	addWebCookie(cookieName,newCookieStr,shop_cart_options);
	return true;
}
/**
 * 购酒商城重置购物车cookie中商品的数量
 * @param cookieName			cookie名称
 * @param productId				要重置的产品ID
 * @param quantity       		要重置的产品数量
 * @return 
 */
function resetEmallCartCookieProduct(cookieName,productId,quantity){
	var beforeCookieStr = getWebCookie(cookieName);
	var newCookieStr = resetEmallCartCookieProductString(beforeCookieStr,productId,quantity);
	addWebCookie(cookieName,newCookieStr,shop_cart_options);
}
/**
 * 快喝重置购物车cookie中商品的数量
 * @param cookieName			cookie名称
 * @param productId				要重置的产品ID
 * @param quantity       		要重置的产品数量
 * @param price					商品价格
 * @param name					商品名称
 * @param storeId				门店编码
 * @return 
 */
function resetQuickCartCookieProduct(cookieName,productId,quantity,price,name,storeId){
	var beforeCookieStr = getWebCookie(cookieName);
	var newCookieStr = resetQuickCartCookieProductString(beforeCookieStr,productId,quantity,price,name,storeId);
	addWebCookie(cookieName,newCookieStr,shop_cart_options);
	
}
/**
 * 购酒商城给定cookieString增减购物车cookie中商品的数量
 * @param beforeStr				当前cookie的值
 * @param productId				要增加的产品ID
 * @param productQuantity       要增加的产品的数量
 * @return String				增加后的cookie值
 */
function buildEmallProductsCookieString(beforeCookieStr,productId,quantity){
	var beforeArr = getEmallCookieCombination(beforeCookieStr);
	var newArr = [];
	var cookieValue = "";
	if((beforeArr == null || beforeArr.length <= 0) && quantity > 0){
		cookieValue = productId + shop_cart_param.product_quantity_separator + quantity;
	}else{
		var isNew = true;
		for(var i = 0;i < beforeArr.length;i++){
			if(beforeArr[i][shop_cart_param.product] != null && beforeArr[i][shop_cart_param.product] != undefined 
					&& beforeArr[i][shop_cart_param.product] != "" && beforeArr[i][shop_cart_param.product] == productId){
				var beforeQuantity = beforeArr[i][shop_cart_param.quantity];
				if((beforeQuantity == null || beforeQuantity == undefined || beforeQuantity == "") && quantity > 0){
					beforeArr[i][shop_cart_param.quantity] = quantity;
					newArr.push(beforeArr[i]);
				}else if(beforeQuantity + quantity > 0){
					beforeArr[i][shop_cart_param.quantity] = (beforeQuantity + quantity > 99) ? 99 : beforeQuantity + quantity;
					newArr.push(beforeArr[i]);
				}
				isNew = false;
			}else{
				newArr.push(beforeArr[i]);
			}
		}
		if(isNew){
			var newObject = {};
			newObject[shop_cart_param.product] = productId;
			newObject[shop_cart_param.quantity] = quantity;
			newArr.push(newObject);
		}
		var isFirst = true;
		if(newArr != null && newArr.length > 0){
			for(var j = 0;j < newArr.length;j++){
				if(!isFirst){
					cookieValue += shop_cart_param.product_separator;
				}
				cookieValue += newArr[j][shop_cart_param.product] + shop_cart_param.product_quantity_separator + newArr[j][shop_cart_param.quantity];
				isFirst = false;
			}
		}
	}
	return cookieValue;
}
/**
 * 购酒商城给定cookieString删除购物车cookie中商品
 * @param beforeStr				当前cookie的值
 * @param productIds			要删除的产品ID
 * @return String				删除后的cookie值
 */
function delCookieEmallProductsString(beforeCookieStr,productIds){
	var beforeArr = getEmallCookieCombination(beforeCookieStr);
	var newArr = [];
	var cookieValue = "";
	if(beforeArr != null && beforeArr.length > 0){
		for(var i = 0;i < beforeArr.length;i++){
			var isContain = false;
			for(var j = 0;j < productIds.length;j++){
				if(beforeArr[i][shop_cart_param.product] != null && beforeArr[i][shop_cart_param.product] != undefined 
						&& beforeArr[i][shop_cart_param.product] != "" && beforeArr[i][shop_cart_param.product] == productIds[j]){
					isContain = true;
				}
			}
			if(!isContain){
				newArr.push(beforeArr[i]);
			}
		}
		var isFirst = true;
		if(newArr != null && newArr.length > 0){
			for(var j = 0;j < newArr.length;j++){
				if(!isFirst){
					cookieValue += shop_cart_param.product_separator;
				}
				cookieValue += newArr[j][shop_cart_param.product] + shop_cart_param.product_quantity_separator + newArr[j][shop_cart_param.quantity];
				isFirst = false;
			}
		}
	}
	return cookieValue;
}
/**
 * 快喝给定cookieString删除购物车cookie中商品
 * @param beforeStr				当前cookie的值
 * @param productIds			要删除的产品ID
 * @param storeId				门店编码
 * @return String				删除后的cookie值
 */
function delCookieQuickProductsString(beforeCookieStr,productIds,storeId){
	var beforeArr = getQuickCookieCombination(beforeCookieStr,null);
	var newArr = [];
	var cookieValue = "";
	if(beforeArr != null && beforeArr.length > 0){
		for(var i = 0;i < beforeArr.length;i++){
			var isContain = false;
			for(var j = 0;j < productIds.length;j++){
				if(beforeArr[i][shop_cart_param.product] != null && beforeArr[i][shop_cart_param.product] != undefined 
						&& beforeArr[i][shop_cart_param.product] != "" && beforeArr[i][shop_cart_param.product] == productIds[j]
						&& beforeArr[i][shop_cart_param.store] == storeId){
					isContain = true;
				}
			}
			if(!isContain){
				newArr.push(beforeArr[i]);
			}
		}
		var isFirst = true;
		if(newArr != null && newArr.length > 0){
			for(var j = 0;j < newArr.length;j++){
				if(!isFirst){
					cookieValue += shop_cart_param.product_separator;
				}
				cookieValue += beforeArr[j][shop_cart_param.product] + shop_cart_param.product_quantity_separator 
								+ beforeArr[j][shop_cart_param.quantity] + shop_cart_param.product_quantity_separator
								+ beforeArr[j][shop_cart_param.price] + shop_cart_param.product_quantity_separator
								+ beforeArr[j][shop_cart_param.name] + shop_cart_param.product_quantity_separator
								+ beforeArr[j][shop_cart_param.store];
				isFirst = false;
			}
		}
	}
	return cookieValue;
}
/**
 * 购酒商城给定cookieString重置购物车cookie中商品的数量
 * @param beforeStr				当前cookie的值
 * @param productId				要重置的产品ID
 * @param quantity       		要重置的产品数量
 * @return String				重置后的cookie值
 */
function resetEmallCartCookieProductString(beforeCookieStr,productId,quantity){
	var beforeArr = getEmallCookieCombination(beforeCookieStr);
	var cookieValue = "";
	var newArr = [];
	if(beforeArr != null && beforeArr.length > 0){
		var length = beforeArr.length;
		for(var i = 0;i < length;i++){
			if(beforeArr[i][shop_cart_param.product] != null && beforeArr[i][shop_cart_param.product] != undefined 
					&& beforeArr[i][shop_cart_param.product] != ""){
				if(beforeArr[i][shop_cart_param.product] == productId){
					if(quantity > 0){
						beforeArr[i][shop_cart_param.quantity] = quantity
						newArr.push(beforeArr[i]);
					}
				}else{
					newArr.push(beforeArr[i]);
				}
			}
		}
	}
	var isFirst = true;
	if(newArr != null && newArr.length > 0){
		for(var j = 0;j < newArr.length;j++){
			if(!isFirst){
				cookieValue += shop_cart_param.product_separator;
			}
			cookieValue += newArr[j][shop_cart_param.product] + shop_cart_param.product_quantity_separator + newArr[j][shop_cart_param.quantity];
			isFirst = false;
		}
	}
	return cookieValue;
}
/**
 * 快喝给定cookieString重置购物车cookie中商品的数量
 * @param beforeStr				当前cookie的值
 * @param productId				要重置的产品ID
 * @param quantity       		要重置的产品数量
 * @param price					商品价格
 * @param name					商品名称
 * @return String				重置后的cookie值
 */
function resetQuickCartCookieProductString(beforeCookieStr,productId,quantity,price,name,storeId){
	var beforeArr = getQuickCookieCombination(beforeCookieStr,null);
	var cookieValue = "";
	var isContain = false;
	var newArr = [];
	if(beforeArr != null && beforeArr.length > 0){
		var length = beforeArr.length;
		for(var i = 0;i < length;i++){
			if(beforeArr[i][shop_cart_param.product] != null && beforeArr[i][shop_cart_param.product] != undefined 
					&& beforeArr[i][shop_cart_param.product] != ""){
				if(beforeArr[i][shop_cart_param.product] == productId && beforeArr[i][shop_cart_param.store] == storeId){
					if(quantity > 0){
						beforeArr[i][shop_cart_param.quantity] = quantity
						newArr.push(beforeArr[i]);
					}
					isContain = true;
				}else{
					newArr.push(beforeArr[i]);
				}
			}
		}
	}
	var isFirst = true;
	if(newArr != null && newArr.length > 0){
		for(var j = 0;j < newArr.length;j++){
			if(!isFirst){
				cookieValue += shop_cart_param.product_separator;
			}
			cookieValue += newArr[j][shop_cart_param.product] + shop_cart_param.product_quantity_separator 
							+ newArr[j][shop_cart_param.quantity] + shop_cart_param.product_quantity_separator
							+ newArr[j][shop_cart_param.price] + shop_cart_param.product_quantity_separator
							+ newArr[j][shop_cart_param.name] + shop_cart_param.product_quantity_separator
							+ newArr[j][shop_cart_param.store];
			isFirst = false;
		}
	}
	if(!isContain && quantity > 0){
		if(cookieValue != null && cookieValue != undefined && cookieValue != ""){
			cookieValue += shop_cart_param.product_separator; 
		}
		cookieValue += productId + shop_cart_param.product_quantity_separator
						+ quantity + shop_cart_param.product_quantity_separator 
						+ price + shop_cart_param.product_quantity_separator
						+ name + shop_cart_param.product_quantity_separator
						+ storeId;
	}
	return cookieValue;
}
/**
 * 购酒商城获取cookie中商品信息
 * @param cookieString					需要解析的cookie值
 * @return json							解析后的商品信息
 */
function getEmallCookieCombination(cookieString){
	if(cookieString == null || cookieString == undefined || cookieString == ""){
		return null;
	}
	var result = [];
	var productQuantitys = cookieString.split(shop_cart_param.product_separator);
	if(productQuantitys == null || productQuantitys == undefined && cookieString.length <= 0){
		return null;
	}
	for(var i = 0;i < productQuantitys.length;i++){
		var object = {};
		var pq = productQuantitys[i].split(shop_cart_param.product_quantity_separator);
		if(pq == null || pq == undefined && pq.length <= 1){
			continue;
		}
		object[shop_cart_param.product] = pq[0];
		object[shop_cart_param.quantity] = parseInt(pq[1]);
		result.push(object);
	}
	return result;
}
/**
 * 快喝获取cookie中商品信息
 * @param cookieString					需要解析的cookie值
 * @param storeId						门店编码
 * @return json							解析后的商品信息
 */
function getQuickCookieCombination(cookieString,storeId){
	if(cookieString == null || cookieString == undefined || cookieString == ""){
		return null;
	}
	var result = [];
	var productQuantitys = cookieString.split(shop_cart_param.product_separator);
	if(productQuantitys == null || productQuantitys == undefined && cookieString.length <= 0){
		return null;
	}
	for(var i = 0;i < productQuantitys.length;i++){
		var object = {};
		var pq = productQuantitys[i].split(shop_cart_param.product_quantity_separator);
		if(pq == null || pq == undefined && pq.length <= 4){
			continue;
		}
		if(storeId == null || storeId == undefined || storeId == "" || pq[4] == storeId){
			object[shop_cart_param.product] = pq[0];
			object[shop_cart_param.quantity] = parseInt(pq[1]);
			object[shop_cart_param.price] = parseInt(pq[2]);
			object[shop_cart_param.name] = pq[3];
			object[shop_cart_param.store] = pq[4];
			result.push(object);
		}
	}
	return result;
}
/**
 * 购酒商城获取购物车列表请求信息
 * @param  type           请求类型							
 * @return jsonStr					
 */
function getEmallCartInfo(type){
	var cookieString = getWebCookie(shop_cart_param.emall_shop_cart_cookie_name);
	if(cookieString == null || cookieString == undefined || cookieString == ""){
		return null;
	}
	var arr = getWebCookieCombination(cookieString);
	if(arr != null && arr.length > 0){
		var jsonObj = {};
		var productIds = [];
		for(var i = 0;i < arr.length;i++){
			productIds.push(arr[i][shop_cart_param.product]);
		}
		jsonObj[shop_cart_param.productInfos] = arr;
		jsonObj[shop_cart_param.productIds] = productIds;
		jsonObj[shop_cart_param.type] = type;
		return JSON.stringify(jsonObj);
	}else{
		return null;
	}
}
/**
 * 获取快喝购物车信息
 * @param  storeId           门店编码							
 * @return jsonStr					
 */
function getQuickCartInfo(storeId){
	var cookieString = getWebCookie(shop_cart_param.quick_shop_cart_cookie_name);
	if(cookieString == null || cookieString == undefined || cookieString == ""){
		return null;
	}
	var arr = getQuickCookieCombination(cookieString,storeId);
	var result = {};
	if(arr != null && arr.length > 0){
		var price = 0;
		var num = 0;
		for(var i = 0;i < arr.length;i++){
			var quantity = 0;
			if(arr[i][shop_cart_param.store] == storeId){
				quantity = arr[i][shop_cart_param.quantity];
				num += arr[i][shop_cart_param.quantity];
				price += arr[i][shop_cart_param.price] * quantity;
			}
		}
		result[shop_cart_param.totalQuantity] = num;
		result[shop_cart_param.totalPrice] = price;
		result[shop_cart_param.productInfos] = arr;
		return result;
	}else{
		return null;
	}
}
/**
 * 获取购酒商城购物车信息						
 * @return jsonStr					
 */
function getEmallCartInfo(){
	var cookieString = getWebCookie(shop_cart_param.emall_shop_cart_cookie_name);
	if(cookieString == null || cookieString == undefined || cookieString == ""){
		return null;
	}
	var arr = getEmallCookieCombination(cookieString);
	var result = {};
	if(arr != null && arr.length > 0){
		var price = 0;
		var num = 0;
		for(var i = 0;i < arr.length;i++){
			var quantity = arr[i][shop_cart_param.quantity];
			num += arr[i][shop_cart_param.quantity];
			price += arr[i][shop_cart_param.price] * quantity;
		}
		result[shop_cart_param.totalQuantity] = num;
		result[shop_cart_param.totalPrice] = price;
		result[shop_cart_param.productInfos] = arr;
		return result;
	}else{
		return null;
	}
}
/**
 * 清空快喝购物车信息
 * @param  storeId           门店编码							
 * @return jsonStr					
 */
function cleanQuickCartInfo(storeId){
	addWebCookie(shop_cart_param.quick_shop_cart_cookie_name,"",shop_cart_options);
}
/**
 * 设置值经纬度到cookie
 * @param  longitude           经度
 * @param  latitude            纬度							
 * @return 					
 */
function addLngAndLat(longitude,latitude){
	addWebCookie(commonParam.longitude,longitude,shop_cart_options);
	addWebCookie(commonParam.latitude,latitude,shop_cart_options);
}
/**
 * 设置定位地址
 * @return 	json 经纬度信息				
 */
function saveAddress(address){
	addWebCookie(commonParam.address,address,shop_cart_options);
}
/**
 * 获取定位地址
 * @return 	json 经纬度信息				
 */
function getAddress(){
	var address = getWebCookie(commonParam.address);
	return address;
}
/**
 * 获取商城首页定位地址
 * @return 	json 经纬度信息				
 */
function getHomeAddress(){
	var address = getWebCookie(commonParam.homeAddress);
	return address;
}
/**
 * 获取经纬度
 * @return 	json 经纬度信息				
 */
function getLngAndLat(){
	var lngAndLat = {};
	var longitude = getWebCookie(commonParam.longitude);
	var latitude = getWebCookie(commonParam.latitude);
	lngAndLat[commonParam.longitude] = longitude;
	lngAndLat[commonParam.latitude] = latitude;
	return lngAndLat;
}
/**
 * 获取商城首页经纬度
 * @return 	json 经纬度信息				
 */
function getMallLngAndLat(){
	var lngAndLat = {};
	var longitude = getWebCookie(commonParam.addressLongitude);
	var latitude = getWebCookie(commonParam.addressLatitude);
	lngAndLat[commonParam.addressLongitude] = longitude;
	lngAndLat[commonParam.addressLatitude] = latitude;
	return lngAndLat;
}
/**
 * 设置城市
 * @return 			
 */
function saveCity(city){
	addWebCookie(commonParam.city,city,shop_cart_options);
}
/**
 * 获取城市
 * @return 				
 */
function getCity(){
	var city = getWebCookie(commonParam.city);
	return city;
}
/**
 * 存放订单回退路径
 * @return 		
 */
function saveBackPath(){
	addWebCookie(commonParam.backPath,window.location.pathname + window.location.search,shop_cart_options);
}
/**
 * 获取订单回退路径
 * @return 		
 */
function getBackPath(){
	return getWebCookie(commonParam.backPath);
}
/**
 * 存放商品来源回退路径
 * @return 		
 */
function saveProductBackPath(){
	addWebCookie(commonParam.productBackPath,window.location.pathname + window.location.search,shop_cart_options);
}
function getProductBackPath(){
	return getWebCookie(commonParam.productBackPath);
}
/**
 * 存放订单确认页面返回标记时间
 * @return 		
 */
function saveOrderTime(timestamp){
	addWebCookie(commonParam.timestamp,timestamp,shop_cart_options);
}