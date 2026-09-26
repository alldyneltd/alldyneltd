<?php
$page_title = "All Dyne Ltd - Checkout";
require_once __DIR__ . '/../app/config/config.php';
require_once BASE_PATH . '/app/middleware/auth.php';
require_once BASE_PATH . '/app/middleware/user.php';

$package_id = $_GET['package_id'];

$stmt_package = mysqli_prepare($conn, "SELECT id, name, price, description FROM packages WHERE id = ?");
mysqli_stmt_bind_param($stmt_package, "i", $package_id);
mysqli_stmt_execute($stmt_package);
$result_package = mysqli_stmt_get_result($stmt_package);

if (mysqli_num_rows($result_package) === 0) {
    header("Location: " . BASE_URL . "index.php");

    mysqli_stmt_close($stmt_package);
    exit();
}

$package = mysqli_fetch_assoc($result_package);
mysqli_stmt_close($stmt_package);

$name = $package['name'];
$price = $package['price'];
$description = $package['description'];

require_once BASE_PATH . '/components/layout/head.php';
require_once BASE_PATH . '/components/layout/header.php';
?>

<div class="custom-main-container min-vh-100 section-spacer">
    <div class="row g-3">
        <div class="col-12 col-md-8">
            <form name="checkout-form" id="checkout-form" action="app/controllers/checkout-controller.php"
                method="POST">
                <input type="hidden" name="package_id" value="<?= e($package_id) ?>">
                <div class="d-flex flex-column gap-3">
                    <div class="card no-hover">
                        <div class="card-body">
                            <div class="form-content mb-3">
                                <div class="form-header">
                                    <div class="icon-box">
                                        <i class="ri-user-line"></i>
                                    </div>
                                    <h3 class="form-title">Informações de Pagamento</h3>
                                </div>
                                <div class="form-body row g-3">
                                    <div class="form-field col-12">
                                        <label for="">Número do Cartão</label>
                                        <input class="form-control" name="card_number" id="card_number"
                                            placeholder="0000 0000 0000 0000" type="text" minlength="19" maxlength="19"
                                            required>
                                    </div>
                                    <div class="form-field col-6">
                                        <label for="">Validade</label>
                                        <input class="form-control" name="card_date" id="card_date" placeholder="MM/AA"
                                            type="text" minlength="5" maxlength="5" required>
                                    </div>
                                    <div class="form-field col-6">
                                        <label for="">Código (CVV)</label>
                                        <input class="form-control" name="card_cvv" id="card_cvv" placeholder="000"
                                            type="text" minlength="4" maxlength="4" required>
                                    </div>
                                    <div class="form-field col-12">
                                        <label for="">Nome do Titular</label>
                                        <input class="form-control" name="card_name" id="card_name"
                                            placeholder="Usuário" type="text" minlength="3" maxlength="100" required>
                                    </div>
                                    <div class="form-field col-12">
                                        <label for="status">Parcelas</label>
                                        <select class="form-select" name="installments" id="installments"
                                            placeholder="Parcelas" autocomplete="off">
                                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?= $i ?>">
                                                    <?= $i . 'x ' . 'de ' . number_format($price / $i, 2, ',', '.') ?>
                                                </option>
                                            <?php endfor ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card no-hover">
                        <div class="card-body">
                            <div class="form-header">
                                <div class="icon-box">
                                    <i class="ri-bank-card-line"></i>
                                </div>
                                <h3 class="form-title">Forma de Pagamento</h3>
                            </div>
                            <div class="form-field col-12">
                                <label for="status">Forma de Pagamento</label>
                                <select class="form-select" name="payment_method" id="payment_method"
                                    placeholder="Forma de Pagamento" autocomplete="off">
                                    <option value="cartao">Cartão</option>
                                    <option value="pix">PIX</option>
                                    <option value="boleto">Boleto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-4 d-flex flex-column gap-3">
            <div class="card no-hover">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-shopping-bag-line"></i>
                    </div>
                    <h3 class="card-title">Resumo do Pedido</h3>
                    <hr>
                    <div class="card no-hover">
                        <div class="card-body">
                            <h3 class="card-title"><?= e($name) ?></h3>
                            <p><?= e($description) ?></p>
                        </div>
                    </div>
                    <!--<hr>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="span-highlight">Subtotal</span>
                        <p>R$ 599,99</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="span-highlight">Desconto</span>
                        <p>-R$ 0,00</p>
                    </div>-->
                    <hr>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h3 class="card-title">Total:</h3>
                        <p class="card-title">R$ <?= e(number_format($price, 2, ',', '.')) ?></p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn primary" form="checkout-form">Finalizar Compra</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once BASE_PATH . '/components/layout/scripts.php';
?>