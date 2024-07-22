function createOrder() {
	let addres = document.getElementById('addres').value;
	if (addres=='') {
		alert('Выберите пункт выдачи заказа');
		return;
	}
	let ids = [];
	let divs = [];
	let cbs = document.querySelectorAll('input[type=checkbox]');
    cbs.forEach(cb => {
      if (cb.checked) {
      	let arr = cb.parentElement.parentElement.id.split(' ', 2);
        ids.push([arr[0], arr[1], document.getElementById('p' + arr[0] + ' ' + arr[1]).innerHTML]);
        divs.push(cb.parentElement.parentElement);
      }
    });
  if (ids.length == 0) {
  	alert('Выберите товары для заказа');
		return;
  }
	fetch('includes/scripts/createOrder.php', {
		method: 'POST',
		mode: 'same-origin',
		credentials: 'include',
		body: JSON.stringify({
			productId: ids,
			pvzaddres: addres
		}),
	})
	.then((response) => response.json())
	.then((data) => {
		if (data.status == 'success') {
			alert('Заказ успешно оформлен!');
			divs.forEach ((div) => {
				div.remove();
			});
		}
		else if (data.status == 'auth') 
			window.open('authentification.php', '_self');
		else {
			alert('Возникла ошибка на стороне сервера, повторите попытку позже');
			alert(data.status);
		}
	});
}