<html>
    <head>
        <title>Trisha Hershberger Official Website</title>
        <meta charset="utf-8">
        <link href='http://fonts.googleapis.com/css?family=Yellowtail' rel='stylesheet' type='text/css'>
        <?php echo $this->_html->includeCss("normalize"); ?>
        <?php echo $this->_html->includeCss("style"); ?>
        <?php echo $this->_html->includeJs("jquery-1.9.0.min"); ?>
        <?php echo $this->_html->includeJs("jquery-ui-1.10.0.custom.min"); ?>
        <?php echo $this->_html->includeJs("modernizr.min"); ?>
        <?php echo $this->_html->includeJs("selectivizr.min"); ?>
    </head>
    <body>
        <header id="main_header">
            <ul class="background_dark_grey group">
                <li><?php echo $this->_html->link("Home","home"); ?></li>
                <li><?php echo $this->_html->link("Host","career/show/host"); ?></li>
                <li><?php echo $this->_html->link("Actress","career/show/actress"); ?></li>
                <li><?php echo $this->_html->link("Model","career/show/model"); ?></li>
                <li><?php echo $this->_html->link("Tradeshow","career/show/tradeshow"); ?></li>
                <li><?php echo $this->_html->link("Voice over Artist","career/show/voice-over-artist"); ?></li>
                <li><?php echo $this->_html->link("Fans","fans"); ?></li>
                <li><?php echo $this->_html->link("Contact","contact"); ?></li>
            </ul>
        </header>
        <div id="content" class="background_grey group">