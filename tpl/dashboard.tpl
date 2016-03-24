<!DOCTYPE html>
<html>
    <head>
      {head}  
    </head>  
    <body class="hold-transition skin-blue sidebar-mini" ng-app="gest" ng-controller="loginController as loginCtrl">        
        <div id="login" ng-show="loginCtrl.isLogged==false" >
            <br><br>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-10 col-lg-offset-5 col-md-offset-4 col-sm-offset-3 col-xs-offset-1 img-rounded bg-info img-thumbnail">                
                <h2 class="text-center">Inicio de sesión</h2>
                <form name="loginForm" class="form form-horizontal">
                    <label for="login">Usuario:</label>
                    <input type="text" ng-model="loginCtrl.user.login" class="form-control">
                    <label for="pass">Contraseña:</label>
                    <input type="password" ng-model="loginCtrl.user.pass" class="form-control"><br>
                    <p class="bg-danger text-danger" ng-show="loginCtrl.loginError!=false">Error de inicio de sesión.</p>
                    <button type="button" class="btn btn-primary" ng-click="loginCtrl.login()">Entrar</button>
                    <hr>
                </form>
            </div>
        </div>
        <div class="wrapper" ng-hide="loginCtrl.isLogged==false">            
            <header-nav></header-nav>
            <side-nav></side-nav>
            <div class="content-wrapper">
                <section ui-view="content">        
                </section>
            </div>
        </div>
        {scripts}
    </body>
</html>
