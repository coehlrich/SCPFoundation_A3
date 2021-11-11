<?php 
    include 'connection.php';
    if ($_POST) {
        $connection->set_charset("utf8mb4");
        $id = $connection->real_escape_string($_POST['id']);
        $created = $id == null;
        $number = $connection->real_escape_string($_POST['number']);
        $class = $connection->real_escape_string($_POST['class']);
        $image = $connection->real_escape_string($_POST['image']);
        $extra = $connection->real_escape_string($_POST['extra']);
        if ($id) {
            $success = $connection->query("update items set number='$number', class='$class', image='$image', extra='$extra' where id='$id'");
        } else {
            $success = $connection->query("insert into items(number, class, image, extra) values('$number', '$class', '$image', '$extra')");
            $id = $connection->insert_id;
        }
        
        if ($success) {
            $success &= $connection->query("delete from item_sections where item=$id");
            if ($success) {
                for ($sectionId = 0; $_POST["title-$sectionId"]; $sectionId++) {
                    $title = $connection->real_escape_string($_POST["title-$sectionId"]);
                    $button = $connection->real_escape_string($_POST["button-$sectionId"]);
                    $content = $connection->real_escape_string($_POST["content-$sectionId"]);
                    $success &= $connection->query("insert into item_sections(item, position, title, button, content) values('$id', '$sectionId', '$title', '$button', '$content')");
                    if (!$success) {
                        break;
                    }
                }
            }
        }
        
        $renderedNumber = str_pad($number, 3, "0", STR_PAD_LEFT);
        if ($created) {
            if ($success) {
                echo "
                    <!DOCTYPE html>
                    <p>Successfully created <a href='scp-item.php?item=$id'>SCP-$renderedNumber</a></p>
                ";
            } else {
                echo "
                    <!DOCTYPE html>
                    <p>Unsuccessfully created SCP-$renderedNumber</p>
                    <p>$connection->error</p>
                    <p><a href='update-item.php'>Back to the form</a></p>
                ";
            }
        } else {
            if ($success) {
                echo "
                    <!DOCTYPE html>
                    <p>Successfully updated <a href='scp-item.php?item=$id'>SCP-$renderedNumber</a></p>
                ";
            } else {
                echo "
                    <!DOCTYPE html>
                    <p>Unsuccessfully updated <a href='scp-item.php?item=$id'>SCP-$renderedNumber</a></p>
                    <p>$connection->error</p>
                    <p><a href='update-item.php?item=$id'>Back to the form</a></p>
                ";
            }
        }
        die();
    } else {
        $id = $_GET['item'];
    }
    
    if ($id) {
        $item = $connection->query("select * from items where id='$id'")->fetch_assoc() or die(mysqli_error($connection));
    }
    $items = $connection->query("select * from items") or die(mysqli_error($connection));
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title id="title">SCP Foundation</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
        <script src="scripts/read.js"></script>
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
                            <a class="nav-link" href="index.php">Home</a>
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
                            <a class="nav-link <?php if (!$id) echo "active"; ?>" href="update-item.php">Add a new Item</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php
            if ($item) {
                $sections = $connection->query("select * from item_sections where item=${item['id']} order by position") or die(mysqli_error($connection));
                $count = $sections->num_rows;
            }
            if ($count < 1) {
                $sections = array(array());
            }
        ?>
        <br>
        <form class="form-group p-3" id="form" method="post" action="update-item.php">
            <?php
                if ($item) {
                    echo "<input type='hidden' name='id' value='${item['id']}'>";
                }
            ?>
            
            <label>Item Number</label>
            <br>
            <input type="text" class="form-control" name="number" placeholder="Type the number of the item without the prefix or leading zeroes" required <?php if ($item) echo "value='${item['number']}'"; ?>>
            <br>
            <label>Item Class</label>
            <br>
            <input type="text" class="form-control" name="class" placeholder="Type the class of the item" required <?php if ($item) echo "value='${item['class']}'"; ?>>
            <br>
            <label>Additional Information</label>
            <br>
            <textarea class="form-control" name="extra" rows="5"><?php if ($item) echo $item['extra']; ?></textarea>
            <br>
            <label>Item Image</label>
            <br>
            <input type="text" class="form-control" name="image" placeholder="Type the name of the image of the item" <?php if ($item) echo "value='${item['image']}'"; ?>>
            <button class="btn btn-primary m-3 add-button" type="button" onclick="addSection(event)" id="add-button-0">+</button>
            <div id="sections">
                <?php 
                    $row = 0;
                    foreach ($sections as $section):?>
                        <div id="section-<?php echo $row; ?>">
                            <div class="bordered shadow p-3 section-form">
                                <div class="row">
                                    <div class="col display-6 section-title">
                                        Section <?php echo $row + 1; ?>
                                    </div>
                                    <div class="col text-end">
                                        <button class="btn btn-danger mb-3 remove-button" type="button" onclick="removeSection(event);" id="remove-button-<?php echo $row; ?>">-</button>
                                    </div>
                                </div>
                                <label>Section Title</label>
                                <input type="text" class="form-control" name="title-<?php echo $row; ?>" placeholder="Type the title for the section" required <?php if ($section['title']) echo "value='${section['title']}'"; ?>>
                                <br>
                                <label>Section Button</label>
                                <br>
                                <input type="text" class="form-control" name="button-<?php echo $row; ?>" placeholder="Type the text for the button for the section to show" required <?php if ($section['button']) echo "value='${section['button']}'"; ?>>
                                <br>
                                <label>Section Content</label>
                                <br>
                                <textarea class="form-control" name="content-<?php echo $row; ?>" rows="5" required><?php echo $section['content']; ?></textarea>
                            </div>
                            <button class="btn btn-primary m-3 add-button" type="button" onclick="addSection(event);" id="add-button-<?php echo $row + 1; ?>">+</button>
                        </div>
                    <?php
                        $row++;
                        endforeach;
                ?>
            </div>
            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary align-middle">Submit</button>
            </div>
        </form>
        <script src="scripts/update_sections.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    </body>

</html>