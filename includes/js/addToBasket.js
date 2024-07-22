function addToBasket(group, id) {
	fetch('includes/scripts/addToBasket.php', {
		method: 'POST',
		mode: 'same-origin',
		credentials: 'include',
		body: JSON.stringify({
			productId: id,
			groupId: group
		}),
	})
	.then((response) => response.json())
	.then((data) => {
		if (data.status == 'success') {
			alert('Товар успешно добавлен в корзину!');
			button = document.getElementById(group.toString() + ' ' + id.toString() + 'button');
			button.innerHTML = '<img src="https://cdn-icons-png.flaticon.com/128/5392/5392794.png" style="width: 25px; height: 25px; vertical-align: middle;"> Убрать из корзины';
			button.setAttribute( "onClick", "removeFromBasket(" + group.toString() + ", " + id.toString() + ")" );
		}
		else if (data.status == 'auth') 
			window.open('authentification.php', '_self');
		else 
			alert('Возникла ошибка на стороне сервера, повторите попытку позже');
	});
}

function addToFavourite(group, id) {
	fetch('includes/scripts/addToFavourite.php', {
		method: 'POST',
		mode: 'same-origin',
		credentials: 'include',
		body: JSON.stringify({
			productId: id,
			groupId: group
		}),
	})
	.then((response) => response.json())
	.then((data) => {
		if (data.status == 'success') {
			alert('Товар успешно добавлен в избранное!');
			span = document.getElementById(group.toString() + ' ' + id.toString() + 'span');
			span.innerHTML = '<img src="pictures/heart2.svg">';
			span.setAttribute( "onClick", "removeFromFavourite(" + group.toString() + ", " + id.toString() + ")" );
		}
		else if (data.status == 'auth') 
			window.open('authentification.php', '_self');
		else 
			alert('Возникла ошибка на стороне сервера, повторите попытку позже');
	});
}