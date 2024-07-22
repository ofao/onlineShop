function removeFromBasket(group, id) {
	fetch('includes/scripts/removeFromUserProduct.php', {
		method: 'POST',
		mode: 'same-origin',
		credentials: 'include',
		body: JSON.stringify({
			productId: id,
			groupId: group,
			field: 'basket'
		}),
	})
	.then((response) => response.json())
	.then((data) => {
		if (data.status == 'success') {
			alert('Товар успешно удален из корзины!');
			if (window.location.href.includes('basket.php')) {
				div = document.getElementById(group.toString() + ' ' + id.toString());
				div.remove();
			}
			else {
				button = document.getElementById(group.toString() + ' ' + id.toString() + 'button');
				button.innerHTML = '<img src="https://cdn-icons-png.flaticon.com/128/5392/5392794.png"  style="width: 25px; height: 25px; vertical-align: middle;"> Добавить в корзину';
				button.setAttribute( "onClick", "addToBasket(" + group.toString() + ", " + id.toString() + ")" );
			}
		}
		else if (data.status == 'auth') 
			window.open('authentification.php', '_self');
		else 
			alert('Возникла ошибка на стороне сервера, повторите попытку позже');
	});
}

function removeFromFavourite(group, id) {
	fetch('includes/scripts/removeFromUserProduct.php', {
		method: 'POST',
		mode: 'same-origin',
		credentials: 'include',
		body: JSON.stringify({
			productId: id,
			groupId: group,
			field: 'favourite'
		}),
	})
	.then((response) => response.json())
	.then((data) => {
		if (data.status == 'success') {
			alert('Товар успешно удален из избранного!');
			if (window.location.href.includes('favourite.php')) {
				div = document.getElementById(group.toString() + ' ' + id.toString());
				div.remove();
			}
			else {
				span = document.getElementById(group.toString() + ' ' + id.toString() + 'span');
				span.innerHTML = '<img src="pictures/heart.svg">';
				span.setAttribute( "onClick", "addToFavourite(" + group.toString() + ", " + id.toString() + ")" );
			}
		}
		else if (data.status == 'auth') 
			window.open('authentification.php', '_self');
		else 
			alert('Возникла ошибка на стороне сервера, повторите попытку позже');
	});
}