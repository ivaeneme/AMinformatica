<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1 class="h2">Bienvenido</h1>
                        <h5>Logueate para mejorar la experiencia en la pagina</h5>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <div class="text-center">
                                    <img src="vistas\assets\img\perfil.png" alt="user" class="img-fluid rounded-circle" width="132" height="132" />
                                </div>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control form-control-lg" type="email" name="email" placeholder="ingresa tu email" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contraseña</label>
                                        <input class="form-control form-control-lg" type="password" name="contrasena" placeholder="Ingresa tu contraseña" />
                                        <small>
                                            <a href="index.php?pagina=recuperarcontrasena">¿Te olvidaste la contraseña?</a>
                                        </small>
                                    </div>
                                    <div>
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" value="remember-me" name="remember-me" checked>
                                            <span class="form-check-label">
                                                Remember me next time
                                            </span>
                                        </label>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary">iniciar Sesion</button>
                                        <p>¿No tenés cuenta? <a href="index.php?pagina=registro_clientes">Crear una nueva cuenta</a></p>
                                    </div>
                                    <?php
                                    $ingreso = new ControladorUsuarios();
                                    $ingreso->ctrIngresoUsuario();
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>