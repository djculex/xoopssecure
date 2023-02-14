<html>
<head>
    <style type="text/css">
        a {
            text-decoration: none;
            color: #1f1f1f;
        }
        a:hover {
            text-decoration: none;
            color: #5d8ba6;
        }
        #wrapper {
            width: 960px;
            margin: 100px auto 0;
            padding-top: 0;
            line-height: 20px;
            background-color:#33FFFF;
            border-style: groove;
            border-color:aqua;
            border-width:medium;
        }
        .greeting {
            margin:0px 0px 0px 25px;
        }
        .infotext {
            margin:10px 0px 0px 25px;
        }
        .title2 {
            margin-bottom: 12px;
            margin-top: 2px;
        }
        .title {
            margin-bottom: 12px;
            margin-top: 4px;
        }
        .filelistheader {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .filelisttable {
            font-size: 11px;
            max-width: 95%;
            border: 1px solid darkcyan;
            padding: 0 15%;
            background-color: white;
            overflow: scroll;
            width: 95%;
            line-break: anywhere;
            font-family: Consolas, sans-serif;
        }
    </style>
</head>
<body>
<!-- Begin Wrapper -->
<div id="wrapper">
    <br>
    <p class="greeting">Hello Admin,</p>
    <p class="infotext">{SENDERNAME} has found something that needs you attention at {SITENAME}</p>
    <!-- End News Navigation -->
    {ISSUES}
</div>
<!-- End Wrapper -->
</body>
</html>