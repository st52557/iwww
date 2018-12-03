<!-- itnetwork.cz/php/zaklady/zaklady-php-kontaktni-emailovy-formular-->
<?php
mb_internal_encoding("UTF-8");

$errorHlaska = '';
$successHlaska = '';


if ($_POST)
{
    if (isset($_POST['jmeno']) && $_POST['jmeno'] &&
        isset($_POST['email']) && $_POST['email'] &&
        isset($_POST['zprava']) && $_POST['zprava'])
    {
        $hlavicka = 'From:' . $_POST['email'];
        $hlavicka .= "\nMIME-Version: 1.0\n";
        $hlavicka .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $adresa = 'nas@email.cz';
        $predmet = 'Nová zpráva z mailformu';
        $uspech = mb_send_mail($adresa, $predmet, $_POST['zprava'], $hlavicka);
        if ($uspech)
        {
            $successHlaska = 'Email byl úspěšně odeslán, brzy vám odpovíme.';
        }
        else
            $errorHlaska = 'Email se nepodařilo odeslat. Zkontrolujte adresu.';

    }
    else
        $errorHlaska = 'Formulář není správně vyplněný!';
}


?>

<p style="font-size: xx-large;background-color: red">
    <?php echo $errorHlaska ?>
</p>

<p style="font-size: xx-large;background-color: lightskyblue">
    <?php echo $successHlaska ?>
</p>


<h1 style="text-align: center">KONTAKT</h1>

<main>

    <div class="center-wrapper">
        <div>
            <h2  style="text-align: center">Nebojte se mě kontaktovat s jakýmkoliv dotazem</h2>
            <hr>


    </div>


        <form method="POST">
            <table>
                <tr>
                    <td>Vaše jméno</td>
                    <td><input name="jmeno" type="text" /></td>
                </tr>
                <tr>
                    <td>Váš email</td>
                    <td><input name="email" type="email" /></td>
                </tr>
            </table>
            <textarea name="zprava"></textarea><br />

            <input type="submit" value="Odeslat" />
        </form>

    <hr>
</main>
