<!DOCTYPE html>
<html lang="en">
    
    <?php
        include 'connection.php';
    ?>
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SCP Foundation</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    </head>
    
    <body class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav" id="navbar-links">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Home</a>
                        </li>
                        <?php 
                            $navItems = array();
                            foreach ($items as $navItem):
                                $hundreds = floor($navItem['number'] / 100);
                                $tens = floor(($navItem['number'] % 100) / 10);
                                $unit = $navItem['number'] % 10;
                                if (!$navItems[$hundreds]) {
                                    $navItems[$hundreds] = array();
                                }
                                
                                if (!$navItems[$hundreds][$tens]) {
                                    $navItems[$hundreds][$tens] = array();
                                }
                        
                                $navItems[$hundreds][$tens][$unit] = $navItem;
                            endforeach;
                        ?>
                        <?php foreach (array_keys($navItems) as $hundred): 
                            $hundredId = "navbar-scp-${hundred}xx"
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="<?php echo $hundredId; ?>" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">SCP-<?php echo $hundred; ?>XX</a>
                                <ul class="dropdown-menu" aria-labelledby="<?php echo $hundredId; ?>">
                                    <?php foreach (array_keys($navItems[$hundred]) as $ten):
                                        $tenId = "navbar-scp-${hundred}${ten}x"
                                        ?>
                                        <li class="dropdown-item dropend">
                                            <a class="nav-link dropdown-toggle" href="#" id="<?php echo $tenId; ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">SCP-<?php echo "$hundred$ten"; ?>X</a>
                                            <ul class="dropdown-menu" aria-labelledby="<?php echo $tenId; ?>">
                                                <?php foreach (array_keys($navItems[$hundred][$ten]) as $unit):
                                                    $navItem = $navItems[$hundred][$ten][$unit];
                                                    $navId = $navItem['id'];
                                                    $navRenderedNumber = str_pad($navItem['number'], 3, "0", STR_PAD_LEFT);
                                                    ?>
                                                    
                                                    <li><a class="dropdown-item" href="scp-item.php?item=<?php echo $navId; ?>">SCP-<?php echo $navRenderedNumber; ?></a></li>
                                                
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="update-item.php">Add a new Item</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1 class="text-center display-5">SCP Foundation</h1>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    </body>
</html>