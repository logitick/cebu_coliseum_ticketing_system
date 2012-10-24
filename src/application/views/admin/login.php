<!DOCTYPE html>
<html lang="en">
<head>
    <title>{title}</title>
    <link type="text/css" rel="stylesheet" href="/media/css/admin_styles.css" media="screen"/>
    <style>
label {
    display:block;
}
#loginWrap {
    margin:10px auto; 
    width: 300px;
    -moz-border-radius: 8px;
    border-radius: 8px;
    border:1px solid #ccc;
    padding:20px;
}
    </style>
</head>
<body>
    <div id="wrap">
        {notification}
        <div id="loginWrap">
            
            <form action="{formAction}" method="post" accept-charset="utf-8">
                
                <label>Username<input type="text" name="username" value="" id="loginUsername"  /></label>
                <label>Password<input type="password" name="password" value="" id="loginPassword"  /></label>
                <input type="submit" value="Login" name="action">
            </form>

        </div>
    </div>
</body>
</html>
