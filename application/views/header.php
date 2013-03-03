<html>
    <head>
        <title>Trisha Hershberger Official Website</title>
        <meta charset="utf-8">
        <link href='http://fonts.googleapis.com/css?family=Yellowtail' rel='stylesheet' type='text/css'>
        <?php echo $this->HTML->includeCss($this->HTML->CombinarCss()); ?>
        <?php echo $this->HTML->includeJs($this->HTML->CombinarJs()); ?>
    </head>
    <body>
        <header id="main_header">
            <ul class="background_dark_grey group">
                <li><?php echo $this->HTML->link("Home","home"); ?></li>
                <li><?php echo $this->HTML->link("Host","career/show/host"); ?></li>
                <li><?php echo $this->HTML->link("Actress","career/show/actress"); ?></li>
                <li><?php echo $this->HTML->link("Model","career/show/model"); ?></li>
                <li><?php echo $this->HTML->link("Tradeshow","career/show/tradeshow"); ?></li>
                <li><?php echo $this->HTML->link("Voice over Artist","career/show/voice-over-artist"); ?></li>
                <li><?php echo $this->HTML->link("Fans","fans"); ?></li>
                <li><?php echo $this->HTML->link("Contact","contact"); ?></li>
            </ul>
        </header>
        <div id="content" class="background_grey group">