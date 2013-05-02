<!DOCTYPE html>
<html>
    <head>  
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Generátor básní</title>

        <meta name="robots" content="all" />
        <meta name="autor" content="Lukáš 'GreenMan' Kurčík" />
        <meta name="description" content="Potrebujete úlohu do školy? Vyjadriť lásku alebo proste len umelevký zážitok? Využite tento generátor básní!" /> 
        <meta name="keywords" content="básne, báseň, generátor básní, láska, romatika, GreenMan" />

        <link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'/>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <!--[if lte IE 7]>
              <link rel='stylesheet' type='text/css' href='css/ie7.css' media='all' />
        <![endif]-->
    </head>
    <body>
        <div id="body">
            <div class='obal'>
                <header role="banner">
                    <h1><a href="/" title="Generátor Básní">Generátor Básní</a></h1>
                </header>
            </div>
            <div class='obal'>
                <article>
                    <?php
                        define("GENERATOR_DIR", "../php/");
                        include 'main.php';
                    ?>
                </article>
            </div>
            <div class='obal'>
                <footer role='contentinfo'>
                    Copyright &copy; 2013 <a href="http://www.greenmanov.net/" title="GreenMan's WebPage" target='_blank'>Lukáš '<span class='green'>GreenMan</span>' Kurčík</a><br />
                    Autor nezodpovedá za žiadnu psychickú, fyzickú ani majetkovú ujmu spôsobenú vygenerovanými básňami.<br />
                    Zdrojové kódy projektu nájdete na <a href='https://github.com/GreenManSK/GeneratorBasni' title='Generátor Básní' target='_blank'>GitHube</a><br />
                    Vytvorené pre súťaž na <a title='devbook.cz' href='http://www.devbook.cz' target='_blank'>devbook.cz</a>
                </footer>
            </div>
        </div>
    </body>
</html>