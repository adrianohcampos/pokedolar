<?php
function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}
//
ob_start("sanitize_output");

$usd        = json_decode(file_get_contents("https://economia.awesomeapi.com.br/all/USD"));
$usd = $usd->USD;
$media      = ($usd->high + $usd->low) / 2;

$dolar      = ceil($media * 100) / 100;
$real       = number_format($dolar, 2, ',', '');
$idpokemon  = $dolar * 100;
$pokemon    = json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $idpokemon));
$update = false;

$update = $usd->create_date;


if (isset($_GET['debug'])) {
    var_dump($usd);
    var_dump($pokemon);
    die;
}

$titlepage = "PokeDólar - #" . $pokemon->id . " " . strtoupper($pokemon->name);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Primary Meta Tags -->
    <title><?= $titlepage ?></title>

    <meta name="title" content="<?= $titlepage ?>">
    <meta name="description" content="Quem é esse pokemon na contação atual do dolar? Acesse e descubra">
    <link rel="icon" href="favicon.ico" />
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://pokedolar.acampos.com.br">
    <meta property="og:title" content="<?= $titlepage ?>">
    <meta property="og:description" content="Quem é esse pokemon na contação atual do dolar? Acesse e descubra">
    <meta property="og:image" content="https://pokedolar.acampos.com.br/logoface.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://pokedolar.acampos.com.br/">
    <meta property="twitter:title" content="<?= $titlepage ?>">
    <meta property="twitter:description" content="Quem é esse pokemon na contação atual do dolar? Acesse e descubra">
    <meta property="twitter:image" content="https://pokedolar.acampos.com.br/logoface.png">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" />

    <style>
        body {
            background: #ffc107;
            background: linear-gradient(to right, #ffc107, #ffeb3b);
        }

        .pricing .card {
            border: none;
            border-radius: 1rem;
            transition: all 0.2s;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }

        .pricing hr {
            margin: 1.5rem 0;
        }

        .pricing .card-title {
            margin: 0.5rem 0;
            font-size: 0.9rem;
            letter-spacing: 0.1rem;
            font-weight: bold;
        }

        .pricing .card-price {
            font-size: 2.5rem;
            margin: 0;
        }

        .pricing .card-price .period {
            font-size: 0.8rem;
        }

        .pricing ul li {
            margin-bottom: 0.3rem;
        }
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-45847705-5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-45847705-5');
    </script>

</head>

<body>
    <section class="pricing pt-4 pb-2">
        <div class="container-fluid">
            <div class="row">
                <!-- Free Tier -->
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mx-auto text-center">
                    <div class=" px-5 mb-3 mx-3"><img src="logo.png" alt="Poke Dólar" class="w-100  logo" /></div>
                    <!-- <h1 class="display-4 text-center mb-4 text-white">Poke Dólar</h1> -->
                    <div class="card mb-3 mb-lg-2">
                        <div class="card-body pokemon ">


                            <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/<?= $pokemon->id ?>.png" alt="Sprite of <?= $pokemon->name ?>" class="w-75" />

                            <h6 class="card-price text-uppercase">
                                <small>$ 1 - R$ <?= $real ?></small><br /><?= $pokemon->name ?>
                            </h6>
                            <hr />

                            <ul class="fa-ul text-left">
                                <li>
                                    <span class="fa-li"><i class="fas fa-check"></i></span>Name:
                                    <?= $pokemon->name ?>
                                </li>
                                <li>
                                    <span class="fa-li"><i class="fas fa-check"></i></span>Nº
                                    <?= $pokemon->id ?>
                                </li>
                                <li>
                                    <span class="fa-li"><i class="fas fa-check"></i></span>Type:
                                    <?= $pokemon->types[0]->type->name ?>
                                </li>
                                <li>
                                    <span class="fa-li"><i class="fas fa-check"></i></span>Weight: <?= $pokemon->weight / 10 ?>kg
                                </li>
                                <li>
                                    <span class="fa-li"><i class="fas fa-check"></i></span>Height: <?= $pokemon->height / 10 ?>m
                                </li>
                            </ul>
                            <? if ($update) { ?>
                                <div class="text-muted"><small>UPDATE <?= $update ?></small></div>
                            <? } ?>

                        </div>
                    </div>

                    <div class="mt-2 text-center">
                        <span style="font-weight: 700; font-size:22px;">pokedolar.acampos.com.br</span>
                        <br>
                        <small>
                            Inspirado por: <a href="https://geekvox.com.br/" class="text-dark" target="_blank">GeekVox</a>
                            e <a href="https://fb.com/PokeDolar/" class="text-dark" target="_blank">Poke Dólar</a><br>
                            Desenvolvido por: <a href="https://acampos.com.br" class="text-dark" target="_blank">Adriano Campos</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
<? ob_end_flush();
