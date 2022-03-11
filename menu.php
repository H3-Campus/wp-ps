<style type="text/css">
/*
#menu li {
		background: #012B55 ;
		list-style: none ;
		color: #fff ;
		display: inline ;
		margin-right: 1px ;
		}
		#menu li a {
		text-decoration: none ;
		text-align: center ;
		background: #012B55 ;
		color: #fff ;
		border: 1px solid #012B55 ;
		font-weight:bold;
		line-height: 1em ;
		padding: 5px 40px ;
	}
	#menu li a:hover, #menu li a:focus, #menu li a:active {
	background: #024282 ;
}
*/
body, ul, li {
	font-size:14px;
	font-family:Arial, Helvetica, sans-serif;
	line-height:21px;
	text-align:left;
}
@page { margin: 1% }

#menu {
	list-style:none;
	width:940px;
	margin:30px auto 0px auto;
	height:43px;
	padding:0px 20px 0px 20px;

	/* Rounded Corners */

	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;

	/* Background color and gradients */

	background: #102457;
	background: -moz-linear-gradient(top, #0272a7, #102457);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0272a7), to(#102457));

	/* Borders */

	border: 1px solid #002232;

	-moz-box-shadow:inset 0px 0px 1px #edf9ff;
	-webkit-box-shadow:inset 0px 0px 1px #edf9ff;
	box-shadow:inset 0px 0px 1px #edf9ff;
}

#menu li {
	float:left;
	display:block;
	text-align:center;
	position:relative;
	padding: 4px 10px 4px 10px;
	margin-right:30px;
	margin-top:7px;
	border:none;
}

#menu li:hover {
	border: 1px solid #777777;
	padding: 4px 9px 4px 9px;

	/* Background color and gradients */

	background: #F4F4F4;
	background: -moz-linear-gradient(top, #F4F4F4, #EEEEEE);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#F4F4F4), to(#EEEEEE));

	/* Rounded corners */

	-moz-border-radius: 5px 5px 0px 0px;
	-webkit-border-radius: 5px 5px 0px 0px;
	border-radius: 5px 5px 0px 0px;
}

#menu li a {
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color: #EEEEEE;
	display:block;
	outline:0;
	text-decoration:none;
	text-shadow: 1px 1px 1px #000;
}

#menu li:hover a {
	color:#161616;
	text-shadow: 1px 1px 1px #ffffff;
}
#menu li .drop {
	padding-right:21px;
	background:url("img/drop.png") no-repeat right 8px;
}
#menu li:hover .drop {
	background:url("img/drop.png") no-repeat right 7px;
}
#menu li .info {
  	padding-right:11px;
	background:url("img/info.png") no-repeat right 7px;
}


.dropdown_1column,
.dropdown_2columns,
.dropdown_3columns,
.dropdown_4columns,
.dropdown_5columns {
	margin:4px auto;
	float:left;
	position:absolute;
	left:-999em; /* Hides the drop down */
	text-align:left;
	padding:10px 5px 10px 5px;
	border:1px solid #777777;
	border-top:none;

	/* Gradient background */
	background:#F4F4F4;
	background: -moz-linear-gradient(top, #EEEEEE, #BBBBBB);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#EEEEEE), to(#BBBBBB));

	/* Rounded Corners */
	-moz-border-radius: 0px 5px 5px 5px;
	-webkit-border-radius: 0px 5px 5px 5px;
	border-radius: 0px 5px 5px 5px;
}

.dropdown_1column {width: 140px;}
.dropdown_2columns {width: 280px;}
.dropdown_3columns {width: 420px;}
.dropdown_4columns {width: 560px;}
.dropdown_5columns {width: 700px;}

#menu li:hover .dropdown_1column,
#menu li:hover .dropdown_2columns,
#menu li:hover .dropdown_3columns,
#menu li:hover .dropdown_4columns,
#menu li:hover .dropdown_5columns {
	left:-1px;
	top:auto;
}

.col_1,
.col_2,
.col_3,
.col_4,
.col_5 {
	display:inline;
	float: left;
	position: relative;
	margin-left: 5px;
	margin-right: 5px;
}
.col_1 {width:130px;}
.col_2 {width:270px;}
.col_3 {width:410px;}
.col_4 {width:550px;}
.col_5 {width:690px;}

#menu .menu_right {
	float:right;
	margin-right:0px;
}
#menu li .align_right {
	/* Rounded Corners */
	-moz-border-radius: 5px 0px 5px 5px;
    -webkit-border-radius: 5px 0px 5px 5px;
    border-radius: 5px 0px 5px 5px;
}

#menu li:hover .align_right {
	left:auto;
	right:-1px;
	top:auto;
}

#menu p, #menu h2, #menu h3, #menu ul li {
	font-family:Arial, Helvetica, sans-serif;
	line-height:21px;
	font-size:12px;
	text-align:left;
	text-shadow: 1px 1px 1px #FFFFFF;
}
#menu h2 {
	font-size:21px;
	font-weight:400;
	letter-spacing:-1px;
	margin:7px 0 14px 0;
	padding-bottom:14px;
	border-bottom:1px solid #666666;
}
#menu h3 {
	font-size:14px;
	margin:7px 0 14px 0;
	padding-bottom:7px;
	border-bottom:1px solid #888888;
}
#menu p {
	line-height:18px;
	margin:0 0 10px 0;
}

#menu li:hover div a {
	font-size:12px;
	color:#015b86;
}
#menu li:hover div a:hover {
	color:#029feb;
}


.strong {
	font-weight:bold;
}
.italic {
	font-style:italic;
}

.imgshadow { /* Better style on light background */
	background:#FFFFFF;
	padding:4px;
	border:1px solid #777777;
	margin-top:5px;
	-moz-box-shadow:0px 0px 5px #666666;
	-webkit-box-shadow:0px 0px 5px #666666;
	box-shadow:0px 0px 5px #666666;
}
.img_left { /* Image sticks to the left */
	width:auto;
	float:left;
	margin:5px 15px 5px 5px;
}

#menu li .black_box {
	background-color:#333333;
	color: #eeeeee;
	text-shadow: 1px 1px 1px #000;
	padding:4px 6px 4px 6px;

	/* Rounded Corners */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;

	/* Shadow */
	-webkit-box-shadow:inset 0 0 3px #000000;
	-moz-box-shadow:inset 0 0 3px #000000;
	box-shadow:inset 0 0 3px #000000;
}

#menu li ul {
	list-style:none;
	padding:0;
	margin:0 0 12px 0;
}
#menu li ul li {
	font-size:12px;
	line-height:24px;
	position:relative;
	text-shadow: 1px 1px 1px #ffffff;
	padding:0;
	margin:0;
	float:none;
	text-align:left;
	width:130px;
}
#menu li ul li:hover {
	background:none;
	border:none;
	padding:0;
	margin:0;
}

#menu li .greybox li {
	background:#F4F4F4;
	border:1px solid #bbbbbb;
	margin:0px 0px 4px 0px;
	padding:4px 6px 4px 6px;
	width:116px;

	/* Rounded Corners */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
}
#menu li .greybox li:hover {
	background:#ffffff;
	border:1px solid #aaaaaa;
	padding:4px 6px 4px 6px;
	margin:0px 0px 4px 0px;
}

#menu li .submenu
    {
        position:fixed;
        margin: -25px 0px 0px 100px;
        z-index: 50;
        visibility:hidden;
            }

</style>


<div id='menu'>
	<li><a href='accueil.php'>Accueil	</a></li>
	<li><a href='H3campus.php'> Wordpress/PrestaShop</a>
	<div class="dropdown_1column">
	  	<div class="col_1">
		  	<ul class="simple">
                <li><a href="WP_Paris.php">Wordpress sur Paris</a></li> 
              	<li><a href="WP_Poissy.php">Wordpress sur Poissy</a></li>
				<li><a href="PS_Paris.php">PrestShop sur Paris</a></li> 
              	<li><a href="PS_Poissy.php">PrestShop sur Poissy</a></li>       	
			</ul>
		</div>
	</div>
	</li>
	<li><a href='#'>Service Info.</a></li>
	
</div>