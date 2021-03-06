<nav class="navbar" id="navbar" style="margin-bottom:0px">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle text-center" style="background-color:#D3D3D3;border:1px solid grey" data-toggle="collapse" data-target="#navbar-collapse">  
        <i class="glyphicon glyphicon-menu-hamburger"></i>           
      </button>
      <a class="navbar-brand" href="/"><img src="/page-icon.png" alt="Logo" style="width:30px;height:30px;float:left;margin-top:-5px" ></img><span style="float:right;margin-top:0px">GeoPostIT</span></a>
    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse">
      <ul class="nav navbar-nav" style="display:none">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <i class="caret"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#">Page 1-1</a></li>
            <li><a href="#">Page 1-2</a></li>
            <li><a href="#">Page 1-3</a></li>
          </ul>
        </li>
        <li><a href="#">Page 2</a></li>
      </ul>
      <form class="navbar-form navbar-left" method="get" action="/posts">
          <div class="form-group">
            <input type="text" class="form-control" name="search_query" placeholder="Search">
            <select name="category" class="form-control">
              <option value="all" disabled selected>Select category</option>
              <?php
                  require('database_credentials.php');
                  $con = new mysqli($servername, $username, $password, $dbname);
                  if ($con->connect_error) {
                      die('<option value="all">All<i class="glyphicon glyphicon-globe"></></option>');
                  } 
                  $sql="SELECT category,icon FROM categories";
                  $cats=mysqli_query($con, $sql);
                  $cats=mysqli_fetch_all($cats,MYSQLI_ASSOC);
                  foreach($cats as $val){
                    if(isset($_GET['category'])){
                      echo '<option value="'.$val['category'].'"'.(($_GET['category']==$val['category'])?'selected':'').'>'.ucwords($val['category']).'</option>';
                    }else{
                      echo '<option value="'.$val['category'].'">'.ucwords($val['category']).'</option>';
                   
                    }
                  }
              ?>
            </select>
          </div>
          
          <button type="submit" class="btn btn-default form-control">Search</button>
        </form>
      <ul class="nav navbar-nav navbar-right">
        <?php
        if(isset($_SESSION['user_data'])){ 
          echo '<li class="user-name">'.$_SESSION['user_data']['user_name'].'</li>
          <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>';
          }else{
            echo '<li ><a href="/login" class="highlight"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                  <li ><a href="/register" class="highlight"><span class="glyphicon glyphicon-user"></span> Register</a></li>
                  ';
          }
        ?>
      </ul>
    </div>
  </div>
</nav>