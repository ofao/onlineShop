<HTML>
<link rel="stylesheet" type="text/css" href="includes/css/admin.css">
<?php include "includes/header.php" ?>
	<?php if($_SESSION['user'] !== 'admin') exit(); ?>
		<aside style='float: left; overflow: hidden; width: 30%; background-color: #274c78; border-radius: 20px 20px;'>
      <ol id='admin-menu'>
	<?php
	//js переменные:
	//data - select * from tableName
	//headers - словарь всех полей
	//perKeys - список первичных ключей, чтобы знать по каким полям удалять
	//arr - используется для операций
	//num - содержит порядковые номера првичных ключей
		if ($conn) {
			$sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = "shop" AND table_type!="VIEW";';
			$result = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_array($result)) {
        echo "<li><a href='admin-razdel.php?table=".$row['table_name']."'>".$row['table_name']."</a></li>";
			}
		}
		$columns = []; #переменная для столбцов таблицы
	?>
        <hr></hr>
        <li><a href='admin.php'><img src='https://cdn-icons-png.flaticon.com/128/876/876225.png' width=30px height=30px style='align: middle;'>Статистика</a></li>
        <li><a href='includes/scripts/exit.php'>Выход</a></li>
      </ol>
    </aside>
    <div style='width: 70%; float: right;'>
	<?php 
		$table_name=$_GET['table'];
		echo "<script>const tableName='".$table_name."';</script>";
		echo "<h1 id='table-name'>".$table_name."</h1>"; 
	?>
      <section style='display: flex; justify-content: space-evenly;'>
        <button class='buttons-editing' id='+' onclick='addRow();'><img src='pictures/add-active.png' width=50px height=50px></button>
        <button class='buttons-editing' id='-' onclick='removeRow();'><img src='pictures/minus-active.png' width=50px height=50px></button>
        <button class='buttons-editing' disabled id='accept' onclick='acceptRow();'><img src='pictures/check-disabled.png' width=50px height=50px></button>
				<button class='buttons-editing' disabled id='rollback' onclick='rollbackRow();'><img src='pictures/failed-disabled.png' width=50px height=50px></button>
      </section>
	<?php
		if ($conn) {
			$sql='SHOW COLUMNS FROM '.$table_name.';'; #получаем столбцы выбранной таблицы
			$result=mysqli_query($conn, $sql);
			echo "<table id='content-table'><tr><th></th>";
			echo "<script>const headers = []; const data = []; let arr = []; const perKeys = [];</script>";
			while ($row=mysqli_fetch_array($result)) {
				$columns[] = $row['Field'];
				echo "<th>".$row['Field']."</th>";
				if (stristr($row['Type'], 'int'))
					echo "<script>headers.push(['".$row['Field']."', 'i', '".$row['Key']."']);</script>";
				else if (stristr($row['Type'], 'double') or stristr($row['Type'], 'float'))
					echo "<script>headers.push(['".$row['Field']."', 'd', '".$row['Key']."']);</script>";
				else
					echo "<script>headers.push(['".$row['Field']."', 's', '".$row['Key']."']);</script>";
				if ($row['Key'] === 'PRI') {
					echo "<script>perKeys.push('".$row['Field']."');</script>";
				}
			}
			echo "</tr>";
			$sql='SELECT * from '.$table_name.';';
			$result=mysqli_query($conn, $sql);
			$count_rows = 1;
			while ($row=mysqli_fetch_array($result)) {
				echo "<tr><td><input id='".$count_rows."' name='".$count_rows."' type='checkbox'></td>";
				foreach ($columns as $column_name) {
					echo "<td class='".$count_rows."elem'>".$row[$column_name]."</td>";
					echo "<script>arr.push('".$row[$column_name]."')</script>";
				}
				echo "<script>data.push(arr); arr = [];</script>";
				echo "</tr>";
				$count_rows += 1;
			}
			echo "</table>";
		}
		mysqli_autocommit($conn, FALSE);
		mysqli_begin_transaction($conn); //запускаем транзакцию
	?>
    </div>
</div>
<?php include "includes/footer.html" ?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js'></script>
<script type='text/javascript'>
	let table = document.getElementById('content-table');
	for (var i = 1; i<table.rows.length; i++) {
		let elem = document.getElementsByClassName(i.toString() + 'elem');
		Array.from(elem).forEach((key) => {
			key.addEventListener('dblclick', editMode.bind(key))
		})
	}
	let nums = [];
		perKeys.forEach((key) => {
			headers.forEach((value) => {
				if (value.includes(key))
					nums.push(headers.indexOf(value));
			})
		})
	function editMode(inp) {
		if (inp.toElement.hasAttribute("contenteditable")) {
			inp.toElement.removeAttribute("contenteditable");
			updateRow(inp.toElement.className);
		}
		else {
			inp.toElement.setAttribute("contenteditable", true);
		}
	}
	function addRow() {
		let appendRow = '</tr>';
		let index = table.innerHTML.indexOf(appendRow);
		let splitted = [table.innerHTML.slice(0, index), table.innerHTML.slice(index)];
		let column_count = table.rows[0].cells.length - 1;
		appendRow += '<tr id="insertedRow" style="background-color: white;"><td><input type="checkbox"></td>';
		for (var i = 0; i < column_count; i++) {
			appendRow += '<td contenteditable> </td>';
		}
		appendRow += '</tr>';
		table.innerHTML = splitted[0] + appendRow + splitted[1];
		document.getElementById('-').setAttribute("disabled", true);
		document.getElementById('-').innerHTML='<img src="pictures/minus-disabled.png" width=50px height=50px>';
		document.getElementById('+').setAttribute("disabled", true);
		document.getElementById('+').innerHTML='<img src="pictures/add-disabled.png" width=50px height=50px>';
		document.getElementById('accept').removeAttribute("disabled");
		document.getElementById('accept').innerHTML='<img src="pictures/check-active.png" width=50px height=50px>';
		document.getElementById('rollback').removeAttribute("disabled");
		document.getElementById('rollback').innerHTML='<img src="pictures/failed-active.png" width=50px height=50px>';
	}

	function removeRow() {
		let table = document.getElementById('content-table');
		let arr = [];
		for (var i = 1; i<table.rows.length; i++) {
			if (document.getElementById(i).checked) {
				arr.push(data[i - 1]);
			}
		}
		fetch('includes/scripts/removeRow.php', {
			method: 'POST',
			mode: 'same-origin',
			credentials: 'include',
			body: JSON.stringify({
				table: tableName,
				nomer: nums,
				columns: perKeys,
				data: arr })
			})
			.then((response) => response.json())
			.then((data) => {
				if (data.status == 'success') {
					alert('Выделенные строки успешно удалены!');
					window.location.reload();
				}
				else if (data.status == 'auth') {
					window.open('authentification.php', '_self');
				}
				else {
					alert('Не удалось удалить строки/n' + data.status);
				}
			});
	}
	function acceptRow() {
		let arr = [];
		let row = document.getElementById('insertedRow').cells;
		for (var j = 1; j < row.length; j++) {
			if (row[j].textContent=='') {
				alert('Заполните все поля!');
				return;
			}
			arr.push(row[j].textContent);
		}
		console.log(arr);
		fetch('includes/scripts/addRow.php', {
			method: 'POST',
			mode: 'same-origin',
			credentials: 'include',
			body: JSON.stringify({ 
					table: tableName,
					types: headers,
					data: arr
				})
			})
			.then((response) => response.json())
			.then((data) => {
				if (data.status == 'success') {
					alert('Строка добавлена!');
				}
				else if (data.status == 'auth') 
					window.open('authentification.php', '_self');
				else 
					alert('Не удалось добвить строку по причине:/n' + data.status);
			});
		rollbackRow();
	}
	function rollbackRow() {
		window.location.reload();
	}
	function updateRow(row_number) {
		row_number = Number(row_number.slice(0, -4));
		let newD = '';
		let old = data[row_number - 1];
		let row = table.rows[row_number].cells;
		let column=0;
		for (var j = 1; j < row.length; j++) {
			if (row[j].innerHTML=='') {
				alert('Заполните все поля!');
				return;
			}
			if (old[j - 1] != row[j].innerHTML) {
				column = j;
				newD = row[j].textContent;
				break;
			}
		}
		if (newD.length == 0)
			return;
		fetch('includes/scripts/updateRow.php', {
			method: 'POST',
			mode: 'same-origin',
			credentials: 'include',
			body: JSON.stringify({ 
					table: tableName,
					types: headers,
					nomer: column,
					newData: newD,
					oldData: old
				})
			})
			.then((response) => response.json())
			.then((data) => {
				if (data.status == 'success') {
					alert('Строка изменена!');
				}
				else if (data.status == 'auth') 
					window.open('authentification.php', '_self');
				else 
					alert('Не удалось изменить строку по причине:/n' + data.status);
			});
		rollbackRow();
	}
</script>
</HTML>
