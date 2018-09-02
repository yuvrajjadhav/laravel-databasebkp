<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Add icon library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.btn {
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 20px;
}

/* Darker background on mouse-over */
.btn:hover {
    background-color: RoyalBlue;
}
</style>
</head>
<body>

<h2>Backup database</h2>

<p>Path:</p>
<a class="btn" href="{{url('db-download')}}"><i class="fa fa-download"></i> Download</a>
<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
 <script src="js/backup-databse.js"/>

</body>
</html>
