<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fake Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-sm-12">
        <form id="fakeLogin" action="{{route('login')}}" method="post">
            <div class="form-group">
              <label for="delegada">Delegada</label>
              <input type="text" class="form-control" name="delegada" placeholder="Delegada" value="PD PARA LOS SECTORES HACIENDA Y DESARROLLO ECONÓMICO, INDUSTRIA Y TURISMO">
            </div>
            <div class="form-group">
              <label for="cedula">Cedula</label>
              <input type="number" class="form-control" name="cedula" placeholder="Cedula" value="1010">
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
    </div>
  </div>
</div>
</body>
</html>
