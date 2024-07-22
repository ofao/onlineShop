function createFeedback(id) {
	if(document.getElementById('star-5').checked) {
		rate=5;
	}
	else if(document.getElementById('star-4').checked) {
	  	rate=4;
	}
	else if(document.getElementById('star-3').checked) {
	  	rate=3;
	}
	else if(document.getElementById('star-2').checked) {
	  	rate=2;
	}
	else if(document.getElementById('star-1').checked) {
	  	rate=1;
	}
	else {
	  	alert('Поставьте оценку товару');
	  	return;
	}
	console.log(window.frames['myframe'].contentDocument.getElementById('textArea'));
	fetch('includes/scripts/createFeedback.php', {
		method: 'POST',
		mode: 'same-origin',
		credentials: 'include',
		body: JSON.stringify({
			orderId: id,
			rating: rate,
			comment: window.frames['myframe'].contentDocument.getElementById('textArea').textContent
		}),
	})
	.then((response) => response.json())
	.then((data) => {
		if (data.status == 'success') {
			alert('Отзыв был добавлен!');
			window.open('orders.php', '_self');
		}
		else if (data.status == 'auth') 
			window.open('authentification.php', '_self');
		else {
			alert('Возникла ошибка на стороне сервера, повторите попытку позже');
			alert(data.status);
		}
	});
}