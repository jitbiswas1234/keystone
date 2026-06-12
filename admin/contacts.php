<?php

include("../config/database.php");

include("../includes/header.php");

include("../includes/navbar.php");

$sql="SELECT * FROM contact ORDER BY created_at DESC";

$result=mysqli_query($conn,$sql);

$total=mysqli_num_rows($result);

?>

<style>

:root{

--pink:#ff3b7a;

--dark:#0a0a1a;

--card:#121228;

--border:rgba(255,255,255,0.06);

--text:#b8b8d1;

}

body{

background:linear-gradient(135deg,#0a0a1a,#140a25);

color:white;

font-family:Poppins;

}

.admin-wrapper{

padding:40px 0;

}

.page-title{

font-size:30px;

font-weight:700;

margin-bottom:25px;

}

/* STAT CARD */

.stat-card{

background:linear-gradient(135deg,#1a1a35,#121228);

border-radius:18px;

padding:25px;

border:1px solid var(--border);

display:flex;

align-items:center;

gap:18px;

transition:.3s;

}

.stat-card:hover{

transform:translateY(-5px);

border-color:var(--pink);

}

.stat-icon{

width:55px;

height:55px;

border-radius:12px;

display:flex;

align-items:center;

justify-content:center;

background:rgba(255,59,122,.15);

color:var(--pink);

font-size:22px;

}

/* SEARCH */

.search-box{

background:#0f0f24;

border:1px solid var(--border);

border-radius:12px;

padding:10px 15px;

color:white;

width:260px;

}

.search-box:focus{

outline:none;

border-color:var(--pink);

}

/* TABLE */

.table-container{

background:var(--card);

border-radius:20px;

border:1px solid var(--border);

margin-top:30px;

overflow:hidden;

}

.admin-table{

width:100%;

border-collapse:collapse;

}

.admin-table th{

background:#161633;

padding:18px;

color:#9ca3af;

font-size:12px;

letter-spacing:1px;

text-transform:uppercase;

}

.admin-table td{

padding:20px;

border-bottom:1px solid var(--border);

}

.admin-table tr:hover{

background:rgba(255,255,255,.02);

}

/* MESSAGE */

.msg-preview{

background:#0f0f24;

padding:12px;

border-radius:10px;

font-size:13px;

border-left:4px solid var(--pink);

color:#c7c7e6;

max-width:320px;

overflow:hidden;

text-overflow:ellipsis;

white-space:nowrap;

}

/* BUTTON */

.action-btn{

width:38px;

height:38px;

border-radius:10px;

display:inline-flex;

align-items:center;

justify-content:center;

border:none;

cursor:pointer;

margin-right:6px;

transition:.3s;

}

.btn-eye{

background:rgba(108,92,255,.15);

color:#8b7fff;

}

.btn-eye:hover{

background:#6c5cff;

color:white;

}

.btn-del{

background:rgba(255,59,122,.15);

color:var(--pink);

text-decoration:none;

}

.btn-del:hover{

background:var(--pink);

color:white;

}

/* MODAL */

.modal-content{

background:#141432;

border-radius:18px;

border:1px solid var(--pink);

}

.modal-header{

border-bottom:1px solid var(--border);

}

.full-msg-box{

background:#0f0f24;

padding:20px;

border-radius:12px;

line-height:1.7;

font-size:14px;

}

@media(max-width:768px){

.msg-preview{

max-width:180px;

}

}

</style>


<div class="container admin-wrapper">

<h2 class="page-title">

<i class="fas fa-envelope me-2" style="color:#ff3b7a"></i>

Contact Reports

</h2>

<div class="d-flex justify-content-between align-items-center">

<div class="stat-card">

<div class="stat-icon">

<i class="fas fa-inbox"></i>

</div>

<div>

<h3><?php echo $total;?></h3>

<small style="color:#9ca3af">

Total Messages

</small>

</div>

</div>

<input 

type="text"

class="search-box"

placeholder="Search..."

onkeyup="searchTable(this.value)"

>

</div>

<div class="table-container">

<table class="admin-table">

<thead>

<tr>

<th>Sender</th>

<th>Message</th>

<th>Date</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<strong>

<?php echo htmlspecialchars($row['name']);?>

</strong>

<br>

<small style="color:#9ca3af">

<?php echo htmlspecialchars($row['email']);?>

</small>

</td>

<td>

<div class="msg-preview">

<?php echo htmlspecialchars($row['message']);?>

</div>

</td>

<td>

<?php

echo date("d M Y",

strtotime($row['created_at'])

);

?>

</td>

<td>

<button 

class="action-btn btn-eye"

onclick="viewMsg(

'<?php echo addslashes($row['name']);?>',

'<?php echo addslashes($row['message']);?>'

)"

>

<i class="fas fa-eye"></i>

</button>

<a 

href="delete_message.php?id=<?php echo $row['id'];?>"

class="action-btn btn-del"

onclick="return confirm('Delete message?')"

>

<i class="fas fa-trash"></i>

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>


<div 

class="modal fade"

id="msgModal"

tabindex="-1"

>

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 id="senderName"></h5>

<button 

class="btn-close btn-close-white"

data-bs-dismiss="modal"

></button>

</div>

<div class="modal-body">

<div 

class="full-msg-box"

id="fullMsg"

>

</div>

</div>

</div>

</div>

</div>


<script>

function viewMsg(name,message){

document.getElementById(

"senderName"

).innerText="From: "+name;

document.getElementById(

"fullMsg"

).innerText=message;

new bootstrap.Modal(

document.getElementById(

"msgModal"

)

).show();

}

function searchTable(value){

value=value.toLowerCase();

let rows=document.querySelectorAll(

".admin-table tbody tr"

);

rows.forEach(row=>{

row.style.display=

row.innerText

.toLowerCase()

.includes(value)

?"":"none";

});

}

</script>


<?php include("../includes/footer.php");?>