<?php $start = microtime(true); ?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Furcode Generator</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/redmond/jquery-ui.css" rel="stylesheet" rev="stylesheet" />
<style>
html, body {
    margin: 0px;
    padding: 0px 0px 50px 0px;
    font-family: sans-serif;
}

nav {
    position: fixed;
    top: 0px;
    right: 0px;
    z-index: 100;
    padding: 3px 7px 4px 10px;
    border-bottom-left-radius: 15px;
    background-color: #ccc;
    box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.2);
}

header {
    text-align: center;
}

.outer, .inner {
    margin: 20px 20px;
    border: 1px solid #A6C9E2;
    border-radius: 10px;
}
.outer > h1, .inner > h2 {
    margin-top: 0px;
    margin-bottom: 0px;
    padding-left: 20px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background-color: #eee;
    background-image: -webkit-gradient(
        linear,
        0% 0%,
        0% 100%,
        from(#eee),
        to(#fff)
    );
    background-image: -moz-linear-gradient(
        0% 100% 90deg,
        #fff,
        #eee
    );
}
h1, h2 .sechead, h1 a:link, h1 a:visited, h1 a:active {
    color: #2E6E9E;
    text-decoration: none;
}
.outer h1 {
    margin-bottom: -10px;
}
.inner {
    padding: 0px 15px 15px 15px;
}
.inner h2 {
    margin: 0px -15px 10px -15px;
}

h2 .btns {
    font-size: 0.6em;
    margin: 0px 5px 0px 10px;
    float: right;
}
.codetbl {
    width: 100%;
    display: none;
}
.codetbl, .codetbl th, .codetbl td {
    border: 1px solid #A6C9E2;
    border-collapse: collapse;
}
.codetbl th, .codetbl td {
    padding: 2px 4px;
}
.codetbl tbody [rowspan] {
    background-color: #eee;
    background-image: -webkit-gradient(
        linear,
        0% 0%,
        0% 100%,
        from(#eee),
        to(#fff)
    );
    background-image: -moz-linear-gradient(
        0% 100% 90deg,
        #fff,
        #eee
    );
}
.codetbl .ui-button {
    font-size: 1.1em;
    font-family: monospace;
}
.codetbl .ui-button-icon-only {
    font-size: 0.9em;
}
.codetbl th[rowspan] {
    vertical-align: top;
}

.codetbl .c_now, .codetbl .c_fut {
    width: 10px; /* minimum width */
}
.codetbl td .ui-button {
    width: 100%;
}

.just-icon {
    background: none;
    border: none;
}
.just-icon span {
    display: inline-block;
}
.sectoggle {
    margin-left: -13px;
    margin-right: -3px;
    cursor: pointer;
}

footer {
    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    margin: 0px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    background-color: #ccc;
    box-shadow: 0px -4px 15px 0px rgba(0, 0, 0, 0.2);
}
footer table {
    width: 100%;
    margin: 0px;
    padding: 0px 10px;
}
footer table td {
    padding: 0px;
}
footer h1 {
    display: inline;
    float: left;
    font-size: 1.5em;
    padding: 0px 10px 0px 0px;
}
footer textarea {
    width: 99%;
}
</style>
<script>
$(function(){
    // Auto-generate IDs for named radios/checkboxes
    $(':radio[name][!id], :checkbox[name][!id]').each(function(){
        this.id = this.name.replace(/[\[\]]/g, '_') + "_" + ($(this).data('custom') ? 'custom_' : '') + this.value.replace(/[^a-z0-9\?~#!$\-+*]/ig, '_');
    });

    // Set-up the "option" buttons
    $(':radio[name*="[opt]"], :checkbox[name*="[opt]"]').each(function(){
        var title = "",
            text = "",
            icon = '',
            showText = true;
        
        switch (this.value) {
            case '':
                title = 'Clear the selection of these options';
                text = 'Clear';
                icon = 'cancel';
                showText = false;
                break;
            case '?':
                title = 'I\'m unsure';
                text = 'Unsure';
                icon = 'help';
                break;
            case '~':
                title = 'This is an approximation';
                text = 'Approx';
                icon = 'transferthick-e-w';
                break;
            case '!':
                title = 'I refuse to prticipate in this category';
                text = 'Refuse';
                icon = 'notice';
                break;
            case '#':
                title = 'Mind your own business!';
                text = 'Private';
                icon = 'locked';
                break;
            case '$':
                title = 'I make a significant amount of money from this!';
                text = 'Professional';
                icon = 'star';
                break;
        }
        
        var lbl = $("<label>")
                .attr('for', this.id)
                .attr('title', title)
                .text(text)
                .insertAfter(this);
        
        $(this).button({
            icons: {
                primary: "ui-icon-" + icon
            },
            text: showText
        }).click(function(){
            var v = $('[name="' + this.name + '"]:checked').val();
            var toggleBtn = $(this).closest('h2').find('.sectoggle');
            if (v == "!" || v == "#") {
                toggleBtn.trigger('collapse');
            } else {
                toggleBtn.trigger('expand');
            }
        });
    });
    
    // Set up the "clear" buttons
    $('.codetbl :radio[value=""]').each(function(){
        $("<label>").attr('for', this.id).append($("<em>Clear</em>")).insertAfter(this);
    });
    
    // Set the text for radios/checkboxes with a value but no existing text
    $('.codetbl :radio[value!=""][title!=""], .codetbl :checkbox[value!=""][title!=""]').each(function(){
        $("<label>").attr('for', this.id).text(this.title).after(" ").insertAfter(this);
        $(this).removeAttr('title');
    });
    
    // Turn everything into jQuery UI buttons
    var tables = $('.codetbl');
    function buttoniseThings(i) {
        $(':radio, :checkbox', tables[i]).button();
        tables[i].style.display = 'table';
        
        if (tables.length > ++i) {
            window.setTimeout(function(){buttoniseThings(i);}, 0);
        }
    }
    window.setTimeout(function(){buttoniseThings(0);}, 0);
    
    // And create button-sets for the rows of option buttons
    $('div h2 .bset').buttonset();
    
    // Set up the behaviour of "clear" buttons. They're radio buttons, so automatically clear the values of other
    //  buttons in the same group, but we don't want the "clear" button to remain "checked" (both for aesthetic reasons
    //  and so that the resulting code doesn't contain a blank prefix character), so we uncheck it here
    $('.btns :radio[value=""], .codetbl :radio[value=""]').change(function(){
       var btn = $(this);
       window.setTimeout(function(){
           btn.prop('checked', false).button('refresh');
           updateCodeDisplay();
       }, 50);
    });
    
    // Set up the "clear" buttons, but only the ones inside tables/lists of values (i.e. NOT with the "options" button
    //  set)
    $('.codetbl :radio[value=""]').css({
       'width': '20px',
       'height': '20px'
    }).button('option', {
       text: false,
       icons: {
           primary: 'ui-icon-cancel'
       }
    });
    
    // Handle showing/hiding the various sections
    $('.sectoggle').hover(function(){
        // Just apply the "hover" state
        $(this).removeClass('ui-state-default').addClass('ui-state-hover');
    }, function(){
        // Remove the "hover" state
        $(this).removeClass('ui-state-hover').addClass('ui-state-default');
    }).bind('expand', function(){
        var $this = $(this);
        var icon = $this.find('.ui-icon');
        var sec = $this.closest('.inner').find('section');
        
        if (!sec.is(':visible')) {
            icon.removeClass('ui-icon-carat-1-e').addClass('ui-icon-carat-1-se');
            sec.show("blind", "fast", function(){
                icon.removeClass('ui-icon-carat-1-se').addClass('ui-icon-carat-1-s');
            });
        }
    }).bind('collapse', function(){
        var $this = $(this);
        var icon = $this.find('.ui-icon');
        var sec = $this.closest('.inner').find('section');
        
        if (sec.is(':visible')) {
            icon.removeClass('ui-icon-carat-1-s').addClass('ui-icon-carat-1-se');
            sec.hide("blind", "fast", function(){
                icon.removeClass('ui-icon-carat-1-se').addClass('ui-icon-carat-1-e');
            });
        }
    }).click(function(){
        // Actually show/hide the content
        var $this = $(this);
        var sec = $this.closest('.inner').find('section');
        
        if (sec.is(':visible')) {
            $this.trigger('collapse');
        } else {
            $this.trigger('expand');
        }
    });
    
    // These define the order in which different code types will be joined
    var INDEX_PREFIX = 5;
    var INDEX_PREFIX_MOD = 7;
    var INDEX_MAIN = 10;
    var INDEX_MOD_0 = 30;
    var INDEX_MOD_1 = 31;
    var INDEX_MOD_2 = 32;
    var INDEX_MOD_3 = 33;
    var INDEX_MOD_4 = 34;
    var INDEX_MOD_5 = 35;
    var INDEX_OPT_UNSURE = 41;
    var INDEX_OPT_APPROX = 42;
    var INDEX_OPT_MONEY = 44;
    
    var I_NOW = 0;
    var I_FUTURE = 1;
    
    var FLAG_REFUSE = "!";
    var FLAG_PRIVATE = "#";
    
    // If a prefix is listed in this array, it will be removed from the output code if it exists with no modifiers
    //  (e.g. the "F" [furry species] code is useless unless a specific species is also selected)
    var codesThatMakeNoSenseAlone = ['F'];
    
    // This function performs the hard work of converting the selected options into the output code
    function updateCodeDisplay() {
        // The code parts are stored in an object of {prefix: [[now,sub,codes],[future,sub,codes]]}
        var parts = {};
        
        // Used to parse the field names, e.g. F[fut][mod][1]
        var nameRegex = /^([A-Z]+)\[([A-Z]+)\](?:\[([A-Z]+)\](?:\[(\d+)\])?)?/i;
        
        // First create our object mapping prefixes to subcodes;
        // Find all checked radios/checkboxes...
        $(':checked').each(function(){
            // ... with a name that matches the regex
            if (nameRegex.exec(this.name)) {
//                console.log(this.name);
                var prefix = RegExp.$1;
//                console.log("Prefix:", prefix);
                
                if (this.value == "!" || this.value == "#") {
                    // Private or refuse answer
                    parts[prefix] = this.value;
                    return;
                }
                
                if (parts[prefix] == undefined) {
                    parts[prefix] = [[],[]]; // now & future
                } else if (!$.isArray(parts[prefix])) {
                    // Probably been set to refuse/private
                    return;
                }
                
                var val = this.value;
                if (val == " ") {
                    // This is a "bare" code with just the prefix; no "++" or "--"-type suffixes
                    val = "";
                }
                
                var nowOrFut = (RegExp.$2 == 'fut') ? I_FUTURE : I_NOW;
                
                // Figure out what position this subcode needs to be in
                var index = INDEX_MAIN;
                if (RegExp.$3) {
                    switch (RegExp.$3) {
                        case 'pmod':
                            index = INDEX_PREFIX_MOD;
                            break;
                        case 'opt':
                            switch (this.value) {
                                case '?': index = INDEX_OPT_UNSURE; break;
                                case '~': index = INDEX_OPT_APPROX; break;
                                case '$': index = INDEX_OPT_MONEY; break;
                            }
                            break;
                        case 'mod':
                            if (RegExp.$4) {
                                switch (RegExp.$4) {
                                    case '0': index = INDEX_MOD_0; break;
                                    case '1': index = INDEX_MOD_1; break;
                                    case '2': index = INDEX_MOD_2; break;
                                    case '3': index = INDEX_MOD_3; break;
                                    case '4': index = INDEX_MOD_4; break;
                                    case '5': index = INDEX_MOD_5; break;
                                }
                            }
                            break;
                    }
                }
                
                if ($(this).data('custom')) {
                    // Custom-type selections should include the entered custom value
                    if ($(this).data('customjustvalue')) {
                        val += $('input:text[name="' + this.name + '"]').val();
                    } else {
                        val += "[" + $('input:text[name="' + this.name + '"]').val() + "]";
                    }
                }
                
                if (this.name.substring(this.name.length - 2, this.name.length) == "[]") {
                    // Array of choices, like a "select some of these modifiers" section
                    if (parts[prefix][nowOrFut][index] == undefined) {
                        parts[prefix][nowOrFut][index] = [];
                    }
                    parts[prefix][nowOrFut][index].push(val);
                } else {
                    parts[prefix][nowOrFut][index] = val;
                }
            }
        });
        
        function collapseSubcodesToString(codes) {
            var codeparts = codes;
            for (var kk in codeparts) {
                if ($.isArray(codeparts[kk])) {
                    codeparts[kk] = codeparts[kk].join('');
                }
            }
            return codeparts.join('');
        }
        
        // Now convert the object to a string
        console.log(parts);
        var code = "";
        for (var k in parts) {
            var codePart;
            if (parts[k] === FLAG_REFUSE) {
                codePart = "!" + k;
            } else if (parts[k] == FLAG_PRIVATE) {
                codePart = k + "#";
            } else {
                // Separate for now & future
                var nowCodePart = collapseSubcodesToString(parts[k][I_NOW]);
                var futCodePart = collapseSubcodesToString(parts[k][I_FUTURE]);
                
                if (nowCodePart == futCodePart) {
                    // Don't include the "future" code if it's the same as the "now" code
                    futCodePart = '';
                }
                
                if (futCodePart) {
                    futCodePart = ">" + futCodePart;
                }
                
                codePart = k + nowCodePart + futCodePart;
            }
            
            // Then, if the prefix either has a modifier code or is allowed to exist on its own...
            if (!(codePart.length == 1 && codesThatMakeNoSenseAlone.indexOf(codePart) != -1)) {
                // ... add it to the output code
                code += codePart + " ";
            }
        }
        
        // Remove the trailing space and show it in the output box
        code = code.substring(0, code.length - 1);
        $('#output').val(code);
    }
    
    // Update the displayed code whenever the input changes
    $('.inner :radio, .inner :checkbox, .inner input[type=text]').change(function(){
        updateCodeDisplay();
    });
    
    // Select all of the output code when the field is focused
    $('#output').bind('focus mouseup', function(e) {
        if (e.type == 'focus') {
            this.select();
        }
        if (e.type == 'mouseup') {
            return false;
        }
    });
    
    $('h2 .sechead[id]').each(function(){
        var groupClass = $(this).closest('.human').length > 0 ? 'human' : 'furry';
        $('#jumpto .'+groupClass).append($("<option>").attr('value', this.id).text("[" + this.id + "] " + $(this).text()));
    });
    $('#jumpto').bind('change click keyup', function(){
        var val = $(this).val();
        if (val.length && val != $(this).data('prevval')) {
            $(this).data('prevval', val);
            $('#'+$(this).val())[0].scrollIntoView();
            window.scrollBy(0, -50);
        }
    });
    
    $('input[type=text]').focus(function(){
        $(':radio[name="'+this.name+'"]').prop('checked', true).button('refresh').change();
    });
});
</script>
</head>
<body>

<nav>
    <label for="jumpto">Jump To Section:</label>
    <select id="jumpto">
        <option> -- Select One -- </option>
        <optgroup class="furry" label="Your Furry Side"></optgroup>
        <optgroup class="human" label="Your Human Side"></optgroup>
    </select>
</nav>

<header>
    <h1><a href="">Fur Code Generator</a></h1>
    <p><em>Based on the Fur Code documentation at <a href="http://captainpackrat.com/furry/furcode.htm">http://captainpackrat.com/furry/furcode.htm</a>.</em></p>
    <p><em>See also the <a href="http://en.wikifur.com/wiki/Furry_code">WikiFur article on the Furry Code</a>.</em></p>
    <p><em>Here is a <a href="http://winterwolf.co.uk/furcode">FurCode Decoder</a></em>.</p>
</header>

<?php
function head($prefix, $title, $unsure=true, $approx=true, $refuse=true, $priv=true, $money=true) {
    ?>
    <h2>
        <span class="just-icon sectoggle ui-state-default">
            <span class="ui-icon ui-icon-carat-1-s"></span>
            <span class="sechead" id="<?=$prefix?>"><?=$title?></span>
        </span>
        <span class="btns">
            <span class="bset">
                <input type="radio" name="<?=$prefix?>[now][opt]" value="" data-clear="1" <?=(!($unsure||$approx||$refuse||$priv))?'disabled':''?>>
                <input type="radio" name="<?=$prefix?>[now][opt]" value="?" <?=(!$unsure)?'disabled':''?>>
                <input type="radio" name="<?=$prefix?>[now][opt]" value="~" <?=(!$approx)?'disabled':''?>>
                <input type="radio" name="<?=$prefix?>[now][opt]" value="!" <?=(!$refuse)?'disabled':''?>>
                <input type="radio" name="<?=$prefix?>[now][opt]" value="#" <?=(!$priv)?'disabled':''?>>
            </span>
            <input type="checkbox" name="<?=$prefix?>[now][opt]" value="$" <?=(!$money)?'disabled':''?>>
        </span>
    </h2>
    <?php
}
?>

<div class="outer furry">
    <h1>Your Furry Side</h1>
    
    <div class="inner">
        <? head("F", "Furry Species", true, true, true, true, false); ?>
        
        <section>
            <p>This code indicates what species your furry persona is. You can indicate your exact species, or just a general type if your personal furry doesn't fit into a particular species.</p>
            
            <p>There's a subtle difference between using "generic" codes and using the ? modifier. For example, FC means you're a canine, but don't fit one of the known species, while FC? means you haven't decided which specific type of canine you are. Similarly, F? means you don't know what kind of furry you are, while FG means you do have a definite shape, but it's just a kind of "generic furry", not any particular species.</p>
            
            <?php
            $species = array(
                'A' => array('Avian', array(
                    "A"=>"Albatross",   "D"=>"Duck",    "E"=>"Eagle",   "F"=>"Falcon",  "G"=>"Seagull",
                    "H"=>"Hawk",        "K"=>"Kiwi",    "O"=>"Owl",     "R"=>"Raven",   "S"=>"Sparrow", "W"=>"Wren",
                )),
                'Ar' => array('<em>Artiodactyla</em> (two-toed hoofed animals)', array(
                    "A"=>"Antelope",    "B"=>"Buffalo",     "C"=>"Cattle",  "D"=>"Deer",    "E"=>"Camel",
                    "F"=>"Giraffe",     "G"=>"Goat",        "L"=>"Llama",   "M"=>"Moose",   "N"=>"Gnu",
                    "P"=>"Pig",         "R"=>"Reindeer",    "S"=>"Sheep",   "W"=>"Warthog", "Z"=>"Gazelle",
                )),
                'C' => array("<em>Canidae</em> (dog family)", array(
                    "A"=>"Arctic fox",      "C"=>"Coyote",  "D"=>"Dingo",   "F"=>"Fox",     "J"=>"Jackal",
                    "M"=>"Domestic mutt",   "W"=>"Wolf",    "X"=>"Maned wolf",
                )),
                'Ce' => array("Centaurs", array(
                    "H"=>"Common (horse-human) centaur",    "Z"=>" Zebra-centaur",
                )),
                'Ch' => array("<em>Chiroptera</em> (bat family)", array(
                    "F"=>"Flying fox or fruit bat",         "V"=>"Vampire bat",
                )),
                'Ct' => array("<em>Cetacea</em> (whale family)", array(
                    "D"=>"Dolphin",
                )),
                'D' => array("Dragons, dinosaurs, and other reptiles", array(
                    "A"=>"Alligator or crocodile",          "C"=>"Coelurosaur (Velociraptor etc)",      "D"=>"Dragon",
                    "K"=>"Carnosaur (Tyrannosaurus etc)",   "L"=>"Lizard",                              "S"=>"Snake", 
                    "T"=>"Tortoise or turtle",
                )),
                'E' => array("<em>Equidae</em> (horse family)", array(
                    "D"=>"Donkey",      "H"=>"Horse",       "Z"=>"Zebra",
                )),
                'Ed' => array("<em>Edentata</em>", array(
                    "A"=>"Anteater",    "R"=>"Armadillo",   "S"=>"Sloth",
                )),
                'F' => array("<em>Felidae</em> (cat family)", array(
                    "B"=>"Bobcat",          "C"=>"Clouded leopard",     "D"=>"Domestic cat",                "H"=>"Cheetah",
                    "J"=>"Jaguar",          "L"=>"Lion",                "M"=>"Puma/cougar/mountain lion",   "O"=>"Ocelot",
                    "P"=>"Leopard/panther", "S"=>"Snow leopard",        "T"=>"Tiger",                       "V"=>"Serval",
                    "W"=>"Wild cat",        "X"=>"Lynx",
                )),
                'G' => array("Generic furry", array()),
                'H' => array("<em>Herpestidae</em> (mongoose family)", array(
                    "K"=>"Meerkat",         "M"=>"Mongoose",
                )),
                'Hy' => array("<em>Hyaenidae</em> (hyena family)", array(
                    "H"=>"Hyena",
                )),
                'I' => array("<em>Insectivora</em>", array(
                    "H"=>"Hedgehog",        "M"=>"Mole",
                )),
                'L' => array("<em>Lagomorpha</em> (rabbit family)", array(
                    "H"=>"Hare",        "J"=>"Jackrabbit",      "R"=>"Rabbit",
                )),
                'M' => array("<em>Mustelidae</em> (weasel family)", array(
                    "A"=>"Sable",       "B"=>"Badger",      "E"=>"Ermine/stoat",    "F"=>"Ferret",              "K"=>"Mink",
                    "M"=>"Marten",      "O"=>"Otter",       "P"=>"Polecat",         "R"=>"Ratel/honey badger",  "S"=>"Skunk",
                    "V"=>"Wolverine",   "W"=>"Weasel",
                )),
                'Ma' => array("<em>Marsupialia</em>", array(
                    "B"=>"Wombat",  "K"=>"Kangaroo",    "O"=>"Koala",   "P"=>"Possum",  "T"=>"Tasmanian devil",     "W"=>"Wallaby",
                )),
                'Mo' => array("<em>Monotremata</em>", array(
                    "P"=>"Platypus",
                )),
                'P' => array("<em>Procyonidae</em> (raccoon family)", array(
                    "R"=>"Raccoon",
                )),
                'Pi' => array("<em>Pinnipedia</em> (seal family)", array(
                    "L"=>"Sea lion",    "S"=>"Seal",    "W"=>"Walrus",
                )),
                'Pr' => array("<em>Primates</em>", array(
                    "L"=>"Lemur",
                )),
                'R' => array("<em>Rodentia</em>", array(
                    "B"=>"Beaver",          "G"=>"Grey squirrel",   "M"=>"Mouse",   "P"=>"Porcupine",   "R"=>"Rat",
                    "S"=>"Red squirrel",    "U"=>"Muskrat",
                )),
                'U' => array("<em>Ursidae</em> (bear family)", array(
                    "A"=>"Polar bear",      "B"=>"Black bear",      "G"=>"Grizzly bear/brown bear/Kodiak bear",
                    "P"=>"Giant panda",
                )),
                'V' => array("<em>Viverridae</em> (civet family)", array(
                    "C"=>"Civet",
                )),
                'X' => array("Mythical creatures (other than centaurs and dragons)", array(
                    "A"=>"Gargoyle",        "C"=>"Manticore",   "G"=>"Gryphon",     "H"=>"Hippogriff",  "M"=>"Mermaid/merman",
                )),
                'Z' => array("Polymorph (you change shape at will, with no \"normal\" shape)", array()),
            );
            ?>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th>Family</th>
                        <th>Species</th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="F[now]" value=""></th>
                        <th><input type="radio" name="F[fut]" value=""></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="radio" name="F[now]" value="G" title="FG"></td>
                        <td></td>
                        <th colspan="2"><em>Generic Furry</em></th>
                    </tr>
                    
                    <? foreach ($species as $prefix => $sInfo) { ?>
                        <tr>
                            <td><input type="radio" name="F[now]" value="<?=$prefix?>" title="F<?=$prefix?>"></td>
                            <td><input type="radio" name="F[fut]" value="<?=$prefix?>" title="F<?=$prefix?>"></td>
                            <th rowspan="<?=count($sInfo[1])+3?>"><?=$sInfo[0]?></th>
                            <td><em>Generic/Unspecified</em></td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="F[now]" value="<?=$prefix?>" data-custom="1" title="F<?=$prefix?>[…]"></td>
                            <td></td>
                            <td><input type="text" name="F[now]" placeholder="Custom Species (Now)"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="radio" name="F[fut]" value="<?=$prefix?>" data-custom="1" title="F<?=$prefix?>[…]"></td>
                            <td><input type="text" name="F[fut]" placeholder="Custom Species (Future)"></td>
                        </tr>
                        <? foreach ($sInfo[1] as $k => $v) { ?>
                            <tr>
                                <td><input type="radio" name="F[now]" value="<?=$prefix?><?=$k?>" title="F<?=$prefix?><?=$k?>"></td>
                                <td><input type="radio" name="F[fut]" value="<?=$prefix?><?=$k?>" title="F<?=$prefix?><?=$k?>"></td>
                                <td><?=$v?></td>
                            </tr>
                        <? } ?>
                    <? } ?>
                </tbody>
            </table>
            
            <h3>Variation Modifiers</h3>
            <p>After identifying your basic furry species, one or more modifiers may be added to indicate variations on the theme:</p>
            <? $mods = array(
                "c"=>"Cyborg",
                "f"=>'"Funny animal" (toon)',
                "h"=>"Were-human",
                "m"=>"Magical powers (other than those covered by other codes)",
                "p"=>"Polymorph (the given species is your normal form, but you can change shape)",
                "s"=>"Psychic powers",
                "t"=>"Taur",
                "u"=>"Unicorn",
                "w"=>"Winged (if the species is not normally winged)",
            ); ?>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th>Variation</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($mods as $k => $v) { ?>
                        <tr>
                            <td><input type="checkbox" name="F[now][mod][0][]" value="<?=$k?>" title="<?=$k?>"></td>
                            <td><input type="checkbox" name="F[fut][mod][0][]" value="<?=$k?>" title="<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            <p>For the purposes of the Furry Code, a "taur" is a variation on the animal theme, while a "centaur" is half-human, half-animal (and is counted as a species in its own right). If you have the upper body of a human and the lower body of a zebra, you're a zebra-centaur and your code is <em>FCeZ</em>, while if you look like a zebra all over, but with four legs and two arms, you're a zebra-taur and your code is <em>FEZt</em>.</p>
            
            <h3>Human-to-Animal Scale</h3>
            <p>Next, add a number to indicate where you fall on the human-to-animal scale:</p>
            <? $mods = array(
                "1"=>"Basically human, with minor furry features (perhaps eyes, nose, ears, claws, some fur, etc)",
                "2"=>"Humanoid, with significant furry features (muzzle, tail, etc); this includes centaurs and mer-people",
                "3"=>"Anthropomorphic animal (or taur)",
                "4"=>"Equally comfortable on two or four legs (or, if you're a taur, on four or six)",
                "5"=>"Animal shape, with some unusual features (perhaps hands, speech, etc); this includes most dragons, gryphons, etc",
                "6"=>"Normal animal shape",
            ); ?>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th>Variation</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($mods as $k => $v) { ?>
                        <tr>
                            <td><input type="checkbox" name="F[now][mod][1][]" value="<?=$k?>" title="<?=$k?>"></td>
                            <td><input type="checkbox" name="F[fut][mod][1][]" value="<?=$k?>" title="<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <h3>Character Type</h3>
            <p>Finally, add one or more letters after the number to indicate what your relationship is with your personal furry.</p>
            <? $mods = array(
                "a"=>'Just a general "alter ego"',
                "c"=>"A costume I wear",
                "d"=>"Someone to draw pictures of",
                "f"=>"Imaginary friend",
                "m"=>"Online MU* character (FurryMuck etc)",
                "r"=>"Role-playing game character",
                "s"=>"Guardian spirit or totem",
                "w"=>"Someone to write stories about",
            ); ?>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th>Variation</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($mods as $k => $v) { ?>
                        <tr>
                            <td><input type="checkbox" name="F[now][mod][2][]" value="<?=$k?>" title="<?=$k?>"></td>
                            <td><input type="checkbox" name="F[fut][mod][2][]" value="<?=$k?>" title="<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("A", "Art", true, true, true, true, true); ?>
        
        <section>
            <p>Most furry fans try their paw at furry artwork sooner or later.</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="A[now]" value=""></th>
                        <th><input type="radio" name="A[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $art = array(
                        "++++" => "Art is my life",
                        "+++"  => "My art appears regularly in zines and elsewhere, and people ask me to contribute to their sketchbooks",
                        "++"   => "I have pictures in reasonably well-known zines and/or Web sites",
                        "+"    => "I draw regularly, and someone once said something that could possibly be construed as a compliment",
                        " "    => "I've shown one or two of my pictures to others, and they didn't actually throw up",
                        "-"    => "Tried a few sketches in the privacy of my own home",
                        "--"   => "Never tried",
                        "---"  => "Never tried, never will",
                    ); ?>
                    <? foreach ($art as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="A[now]" value="<?=$k?>" title="A<?=$k?>"></td>
                            <td><input type="radio" name="A[fut]" value="<?=$k?>" title="A<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("C", "Conventions", true, true, true, true, true); ?>
        
        <section>
            <p>How often do you go to/get involved in/get thrown out of furry conventions? (Note: this category, except as noted below, refers specifically to <em>furry</em> cons, not to SF-related cons in general.)</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="C[now]" value=""></th>
                        <th><input type="radio" name="C[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $cons = array(
                        "+++"  => "Been to lots of cons, organised at least one",
                        "++"   => "I'm a regular con-goer, and I've occasionally lent a paw",
                        "+"    => "I've been to several, and plan to go to many more",
                        " "    => "I've been to one",
                        "-"    => "Never been to one, but may do so in future",
                        "--"   => "Not interested",
                        "---"  => "I wouldn't go near one of those places if you paid me",
                        "*"    => "Haven't been to a furry con, but I have been to an SF con",
                        "**"   => "Haven't been to a furry con, but I've helped organise an SF con",
                    ); ?>
                    <? foreach ($cons as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="C[now]" value="<?=$k?>" title="C<?=$k?>"></td>
                            <td><input type="radio" name="C[fut]" value="<?=$k?>" title="C<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("D", "Dressing up", true, true, true, true, true); ?>
        
        <section>
            <p>Many furries are into cross-dressing. Cross-species, that is.</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="D[now]" value=""></th>
                        <th><input type="radio" name="D[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $dressingup = array(
                        "++++" => "I've made plans to be buried in a fursuit",
                        "+++"  => "I'll wear a fursuit at any opportunity (where costumes are not expected)",
                        "++"   => "I'll wear a fursuit at cons/sporting events (where costumes are uncommon)",
                        "+"    => "I'll wear a fursuit for Halloween/masquerades (where costumes are expected)",
                        " "    => "I might wear a fursuit",
                        "-"    => "I'd wear a fursuit if I had to",
                        "--"   => "You must be kidding, I'd die first",
                    ); ?>
                    <? foreach ($dressingup as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="D[now]" value="<?=$k?>" title="D<?=$k?>"></td>
                            <td><input type="radio" name="D[fut]" value="<?=$k?>" title="D<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <p>And a little extra...</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="D[now][pmod]" value="m" title="Dm"></td>
                        <td><input type="checkbox" name="D[fut][pmod]" value="m" title="Dm"></td>
                        <td>I've made my own fursuit!</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("H", "Hugs", true, true, true, true, true); ?>
        
        <section>
            <p>A popular activity among furries when they gather, although not to everyone's taste.</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="H[now]" value=""></th>
                        <th><input type="radio" name="H[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $hugs = array(
                        "+++"  => "If it moves, I'll hug it (if it doesn't move, I'll hug it until it moves)",
                        "++"   => "I'll hug anyone I know, given a faint excuse",
                        "+"    => "I'll accept hugs, and maybe even give the occasional one",
                        " "    => "Well, OK, you can hug me if you really want to",
                        "-"    => "Please don't, unless we know each other very well",
                        "--"   => "No way",
                        "---"  => "Argh! Don't touch me, you pervert!",
                    ); ?>
                    <? foreach ($hugs as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="H[now]" value="<?=$k?>" title="H<?=$k?>"></td>
                            <td><input type="radio" name="H[fut]" value="<?=$k?>" title="H<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("M", "Mucking and mudding", true, true, true, true, true); ?>
        
        <section>
            <p>Use this code to indicate how deeply involved you are in online multi-user universes such as FurryMuck.</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="M[now]" value=""></th>
                        <th><input type="radio" name="M[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $mucks = array(
                        "++++" => "I'm a Wizard, tremble before my might!",
                        "+++"  => "Someday I may get around to trying Real Life, but it's not high on my agenda",
                        "++"   => "I've got characters on several MU*s, and frequently get frustrated when commands don't work in RL",
                        "+"    => "I'm a regular on at least one furry MU*",
                        " "    => "Tried them once or twice, may do it again sometime",
                        "-"    => "Never been able to dredge up enough interest (or time) to try it",
                        "--"   => "Those things are for weenies",
                        "---"  => "You guys are pathetic, get a life!",
                    ); ?>
                    <? foreach ($mucks as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="M[now]" value="<?=$k?>" title="M<?=$k?>"></td>
                            <td><input type="radio" name="M[fut]" value="<?=$k?>" title="M<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("P", "Plush critters", true, true, true, true, true); ?>
        
        <section>
            <p>Well, until those genetic engineers get their act together and give us some real furries, we'll have to make do with these...</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="P[now]" value=""></th>
                        <th><input type="radio" name="P[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $plushies = array(
                        "++++" => "Where'd my plushie go? Oh, it's buried under my stuffies",
                        "+++"  => "I collect every one I find of a certain species (or two, or three...)",
                        "++"   => "I've got a collection of several favourites",
                        "+"    => "I have been known to cuddle a few",
                        " "    => "I like them, they sit on my shelves collecting dust",
                        "-"    => "No thanks, they'll sit on my shelves collecting dust",
                        "--"   => "Kid stuff, I'd sooner hug a pincushion",
                    ); ?>
                    <? foreach ($plushies as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="P[now]" value="<?=$k?>" title="P<?=$k?>"></td>
                            <td><input type="radio" name="P[fut]" value="<?=$k?>" title="P<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("R", "Realism vs. tooniness", true, true, true, true, false); ?>
        
        <section>
            <p>Do you prefer your furries anatomically correct (if that makes sense), or cartoonish, or somewhere in between?</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="R[now]" value=""></th>
                        <th><input type="radio" name="R[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $real = array(
                        "+++"  => "What do you mean it isn't a photograph?",
                        "++"   => "Figments of the imagination have anatomies too, you know",
                        "+"    => "I like both, but prefer realistic furries",
                        " "    => "No particular preference",
                        "-"    => "I like both, but prefer toons",
                        "--"   => "The toonier the better",
                        "---"  => "If it's not Super-Deformed I don't want to know about it",
                    ); ?>
                    <? foreach ($real as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="R[now]" value="<?=$k?>" title="R<?=$k?>"></td>
                            <td><input type="radio" name="R[fut]" value="<?=$k?>" title="R<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("T", "Transformation", true, true, true, true, false); ?>
        
        <section>
            <p>If you had the chance, would you want to become a real furry?</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="T[now]" value=""></th>
                        <th><input type="radio" name="T[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $trans = array(
                        "++++" => "Never mind the fine print, where do I sign?",
                        "+++"  => "Definitely! (as long as I get to choose the species)",
                        "++"   => "Yes, if it's reversible",
                        "+"    => "Probably, as long as I wasn't the first guinea pig (or whatever...)",
                        " "    => "I'd have to think about it carefully",
                        "-"    => "Not personally",
                        "--"   => "What a horrible idea!",
                    ); ?>
                    <? foreach ($trans as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="T[now]" value="<?=$k?>" title="T<?=$k?>"></td>
                            <td><input type="radio" name="T[fut]" value="<?=$k?>" title="T<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("W", "Writing", true, true, true, true, true); ?>
        
        <section>
            <p>For those who have tried their paw at telling us some grim (or even not-so-grim) furry tails.</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="W[now]" value=""></th>
                        <th><input type="radio" name="W[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $writing = array(
                        "++++" => "I've out-sold Anne McCaffrey <em>(furry-related)</em>",
                        "****" => "I've out-sold Anne McCaffrey <em>(<strong>not</strong> furry-related)</em>",
                        "+++"  => "I've sold a book <em>(furry-related)</em>",
                        "***"  => "I've sold a book <em>(<strong>not</strong> furry-related)</em>",
                        "++"   => "I've sold a story to a <em>real</em> magazine (duck) <em>(furry-related)</em>",
                        "**"   => "I've sold a story to a <em>real</em> magazine (duck) <em>(<strong>not</strong> furry-related)</em>",
                        "+"    => "I've sold stuff to fanzines <em>(furry-related)</em>",
                        "*"    => "I've sold stuff to fanzines <em>(<strong>not</strong> furry-related)</em>",
                        " "    => "I've written a story that somebody else has read",
                        "-"    => "I have these scribblings but <em>nobody</em> is ever going to see them!",
                        "--"   => "Never written a word of fiction (Inland Revenue forms excepted)",
                        "---"  => "Illiterate",
                    ); ?>
                    <? foreach ($writing as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="W[now]" value="<?=$k?>" title="W<?=$k?>"></td>
                            <td><input type="radio" name="W[fut]" value="<?=$k?>" title="W<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("Z", "Zines", true, true, true, true, true); ?>
        
        <section>
            <p>Use this code to indicate how deeply involved you are in the furry fanzine and comic scene.</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="Z[now]" value=""></th>
                        <th><input type="radio" name="Z[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $zines = array(
                        "++++" => "Publisher/editor/other staff on a professional furry-related publication (should have the <em>\$</em> modifier)",
                        "+++"  => "I work on a regularly-published amateur zine, or I'm a frequently-published author/artist",
                        "++"   => "I've been published, or directly involved in a publication, at least once",
                        "+"    => "I have a good collection, and buy at least one title regularly",
                        " "    => "I have a few furry zines",
                        "-"    => "Not really interested",
                        "--"   => "Comics are for kids",
                    ); ?>
                    <? foreach ($zines as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="Z[now]" value="<?=$k?>" title="Z<?=$k?>"></td>
                            <td><input type="radio" name="Z[fut]" value="<?=$k?>" title="Z<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("S", "Furry Sex", true, true, true, true, true); ?>
        
        <section>
            <p>Like it or not, S-*-X seems to be part of furry life. Use this code, if you wish, to indicate the sex (and sex life) of your furry persona. Feel free to use the <em>#</em> ("mind your own business") or <em>!</em> ("nothing to do with me") modifiers if you prefer.</p>
            
            <h3>Character's Sex</h3>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="S[now][pmod]" value=""></th>
                        <th><input type="radio" name="S[fut][pmod]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sex = array(
                        "f"     => "Female",
                        "h"     => "Hermaphrodite",
                        "m"     => "Male",
                        "p"     => "Polymorph (can change sex at will)",
                    ); ?>
                    <? foreach ($sex as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="S[now][pmod]" value="<?=$k?>" title="S<?=$k?>"></td>
                            <td><input type="radio" name="S[fut][pmod]" value="<?=$k?>" title="S<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <h3>What's Their Sex Life Like?</h3>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="S[now]" value=""></th>
                        <th><input type="radio" name="S[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sexlife = array(
                        "+++"  => "What do you mean there are other things to do?",
                        "++"   => "Ready, willing, and able",
                        "+"    => "I've had TinySex",
                        " "    => "Never had TinySex, but wouldn't rule it out",
                        "-"    => "Celibate",
                        "--"   => "You people are sick!",
                    ); ?>
                    <? foreach ($sexlife as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="S[now]" value="<?=$k?>" title="S<?=$k?>"></td>
                            <td><input type="radio" name="S[fut]" value="<?=$k?>" title="S<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<div class="outer human">
    <h1>Your Human Side</h1>
    
    <div class="inner">
        <? head("RL", "What you do in Real Life", true, true, true, true, false); ?>
        
        <section>
            <p>Unfortunately, we can't yet be furries in Real Life (you know -- that boring game you play when the computer's down and there's nothing on TV), so we have to find something else to do. This code indicates what you do for a living, or what you're studying to do for a living, or what you do to avoid living.</p>
            <p>(The <em>$</em> modifier shouldn't be used here, since it's taken for granted that this represents your day job, or the nearest thing you have to one.)</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="RL[now]" value=""></th>
                        <th><input type="radio" name="RL[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rl = array(
                        "RLA"   => "Art",
                        "RLBM"  => "Business/management",
                        "RLC"   => "Craft",
                        "RLCI"  => "Construction industry",
                        "RLCT"  => "Computers/information technology",
                        "RLE"   => "Engineering",
                        "RLET"  => "Education/teaching",
                        "RLF"   => "Farming",
                        "RLFB"  => "Finance/banking",
                        "RLGP"  => "Government/public service",
                        "RLL"   => "Law",
                        "RLLW"  => "Literature/writing",
                        "RLM"   => "Music",
                        "RLMA"  => "Military/armed forces",
                        "RLMC"  => "Media/communications",
                        "RLMH"  => "Medicine (human)",
                        "RLRB"  => "Retail business",
                        "RLS"   => "Science",
                        "RLTH"  => "Theatre",
                        "RLTI"  => "Transport industry",
                        "RLVM"  => "Veterinary medicine",
                        "RLAT"  => "I'm a furry of all trades <em>(\"Specialisation is for insects!\" -- Robert Heinlein)</em>",
                        "RLU"   => "Undecided (generally used by young students who haven't picked a major yet)",
                        "RL-"   => "No qualifications, no job, no complaints!",
                    ); ?>
                    <? foreach ($rl as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="RL[now]" value="<?=$k?>" title="RL<?=$k?>"></td>
                            <td><input type="radio" name="RL[fut]" value="<?=$k?>" title="RL<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <p>And a little extra...</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="RL[now][mod][0]" value="*" title="RL*"></td>
                        <td><input type="checkbox" name="RL[fut][mod][0]" value="*" title="RL*"></td>
                        <td>I'm trained or experienced in this field, but haven't managed to persuade anyone to actually pay me to do it yet (this doesn't apply to <em>RLU</em> or <em>RL-</em>).</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("a", "Age", false, true, true, true, false); ?>
        
        <section>
            <p>So, how long have you been a human being? <em>("Three foot six!")</em></p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="a[now]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $age = array(
                        "++++" => "60+ years",
                        "+++"  => "50-59 years",
                        "++"   => "40-49 years",
                        "+"    => "30-39 years",
                        " "    => "20-29 years",
                        "-"    => "10-19 years",
                        "--"   => "Under 10 years",
                    ); ?>
                    <? foreach ($age as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="a[now]" value="<?=$k?>" title="a<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td><input type="radio" name="a[now]" value=" " data-custom="1" data-customjustvalue="1" title="a##"></td>
                        <td><input type="text" name="a[now]" placeholder="Specific Age"></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("c", "Computers", true, true, true, true, true); ?>
        
        <section>
            <p>Since you're probably reading this on a computer screen, there's a good chance you have some familiarity with the technology. Use this code to tell us how much of a Computer Geek you are.</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="c[now]" value=""></th>
                        <th><input type="radio" name="c[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $computers = array(
                        "++++" => "I'll be first in line to get a cybernetic interface installed in my skull!",
                        "+++"  => "Hey, if there was anything else to life, there'd be a newsgroup about it",
                        "++"   => "Computers are a large part of my life; I spend time every day in front of one; I've tried my hand at programming",
                        "+"    => "Computers are fun; I can use some software without resorting to the manual; I play a mean game of [insert favourite game]",
                        " "    => "Computers are just a tool; I use one when it serves my purpose",
                        "-"    => "I'm nervous of anything more complicated than my microwave",
                        "--"   => "Where's the \"on\" switch? Better yet, where's the \"off\" switch?",
                        "---"  => "They're taking over the world! Smash the machines! Up the Luddites!",
                    ); ?>
                    <? foreach ($computers as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="c[now]" value="<?=$k?>" title="c<?=$k?>"></td>
                            <td><input type="radio" name="c[fut]" value="<?=$k?>" title="c<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <p>If your rating is at least <em>c</em>, add one or more of the following letters to indicate your preferred operating environment:</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $os = array(
                        "a"     => "Amiga",
                        "b"     => "BSD Unix",
                        "d"     => "MS-DOS",
                        "l"     => "Linux",
                        "m"     => "Macintosh",
                        "n"     => "Windows",
                        "o"     => "OS/2",
                        "u"     => "Unix (commercial)",
                        "v"     => "VMS",
                        "w"     => "Windows 3.x",
                    ); ?>
                    <? foreach ($os as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="c[now][pmod]" value="<?=$k?>" title="c<?=$k?>"></td>
                            <td><input type="radio" name="c[fut][pmod]" value="<?=$k?>" title="c<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("d", "Doom, Quake, etc.", true, true, true, true, true); ?>
        
        <section>
            <p>ID Games' <em>Doom</em> and <em>Quake</em>, and related games (<em>Dark Forces</em>, <em>Duke Nukem 3D</em>, <em>Heretic</em>, <em>Hexen</em>, etc), seem to be at least as hugely popular among furries as they are among the population at large. What is it about running around with heavy-calibre weaponry shooting the shit out of everything in sight that appeals to us? Or does that question answer itself?</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="d[now]" value=""></th>
                        <th><input type="radio" name="d[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $doom = array(
                        "++++" => "I work for ID, bow down before me",
                        "+++"  => "I can solve every level in Nightmare mode with my eyes shut; I crank out new WAD files daily",
                        "++"   => "I've got pretty good at it; I can get through most levels easily; I've downloaded and played other WADs",
                        "+"    => "It's a fun, action game that is a nice diversion on a lazy afternoon",
                        " "    => "I've played the game and I'm pretty indifferent",
                        "-"    => "I've played the game and really didn't think it was all that impressive",
                        "--"   => "I miss Zork",
                        "---"  => "All this violence is sickening; there ought to be a law",
                    ); ?>
                    <? foreach ($doom as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="d[now]" value="<?=$k?>" title="d<?=$k?>"></td>
                            <td><input type="radio" name="d[fut]" value="<?=$k?>" title="d<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("e", "Education", true, true, true, true, false); ?>
        
        <section>
            <p>How far have you managed to crawl up the academic ladder?</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="e[now]" value=""></th>
                        <th><input type="radio" name="e[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $education = array(
                        "++++" => "Doctorate, or the equivalent",
                        "+++"  => "Master's degree, or the equivalent",
                        "++"   => "Bachelor's degree, or the equivalent",
                        "+"    => "Some tertiary education",
                        " "    => "Finished high school",
                        "-"    => "Haven't finished high school",
                        "--"   => "Haven't started high school",
                        "*"    => "Learned everything there is to know about life from <em>The Hitch-Hiker's Guide to the Galaxy</em>, my collection of <em>Omaha</em> comics, and late-night reruns of <em>Star Trek</em> and <em>The Prisoner</em>",
                        "**"   => "Graduate degree from the School of Hard Knocks",
                    ); ?>
                    <? foreach ($education as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="e[now]" value="<?=$k?>" title="e<?=$k?>"></td>
                            <td><input type="radio" name="e[fut]" value="<?=$k?>" title="e<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("f", "Real life furriness factor", true, true, true, true, false); ?>
        
        <section>
            <p>How far does furryness spill over into your other life? Or, to put it another way, how far do you allow the mundane world to get in the way of the important things in life?</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="f[now]" value=""></th>
                        <th><input type="radio" name="f[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rlfurriness = array(
                        "++++" => "I am not a human, I am a furry!",
                        "+++"  => "I've been known to bark at people I don't know to greet them",
                        "++"   => "I've been known to bark at friends to greet them",
                        "+"    => "I make frequent jokes, show friends my furry art collection",
                        " "    => "I make an occasional reference to my furriness",
                        "-"    => "I only tell close friends",
                        "--"   => "I tell <em>nobody</em>",
                    ); ?>
                    <? foreach ($rlfurriness as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="f[now]" value="<?=$k?>" title="f<?=$k?>"></td>
                            <td><input type="radio" name="f[fut]" value="<?=$k?>" title="f<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("h", "Housing", true, true, true, true, false); ?>
        
        <section>
            <p>What sort of home do you live in?</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="h[now]" value=""></th>
                        <th><input type="radio" name="h[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $housing = array(
                        "++++" => "Married ... with children",
                        "+++"  => "Married, or shacked up with your SO on a long-term basis",
                        "++"   => "Living with one or more fellow furries",
                        "+"    => "Living with one or more people who know nothing about furriness",
                        " "    => "Living alone, other furries come to visit",
                        "-"    => "Living alone, get out once a week to buy food, all surfaces covered in computers and/or zines",
                        "--"   => "Living in a cave with 47 computers and a T1 line",
                        "*"    => "I'm still stuck living with my parents",
                        "**"   => "I'm not sure where I live any more; my workplace/lab seems like home to me",
                        "***"  => "Homeless",
                    ); ?>
                    <? foreach ($housing as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="h[now]" value="<?=$k?>" title="h<?=$k?>"></td>
                            <td><input type="radio" name="h[fut]" value="<?=$k?>" title="h<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("i", "Internet", true, true, true, true, true); ?>
        
        <section>
            <p>The Internet, and its various sub-media and related media (Usenet, email, World-Wide Web, MUCKs and MUDs, IRC, FTP sites, and gods know what else), has quickly become a leading medium of communication among furries (not to mention the rest of the technologically aware universe). How deeply have you dived in?</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="i[now]" value=""></th>
                        <th><input type="radio" name="i[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $internet = array(
                        "+++"  => "I'm a Webmaster/site administrator",
                        "++"   => "I spend most of my spare time surfing the Web, and read any newsgroup that catches my interest",
                        "+"    => "I browse the Web regularly, and read a handful of newgroups",
                        " "    => "I have a browser and a connection, and even use them occasionally",
                        "-"    => "Not connected yet",
                        "--"   => "The Internet sucks; it's all just a flash in the pan anyway",
                        "---"  => "It's a dangerous, subversive, perverted abomination that needs to be banned before people stop voting for me!",
                    ); ?>
                    <? foreach ($internet as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="i[now]" value="<?=$k?>" title="i<?=$k?>"></td>
                            <td><input type="radio" name="i[fut]" value="<?=$k?>" title="i<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <p>And a little extra...</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="i[now][pmod]" value=""></th>
                        <th><input type="radio" name="i[fut][pmod]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="radio" name="i[now][pmod]" value="w" title="iw"></td>
                        <td><input type="radio" name="i[fut][pmod]" value="w" title="iw"></td>
                        <td>I have my own home page on the Web</td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="i[now][pmod]" value="wf" title="iwf"></td>
                        <td><input type="radio" name="i[fut][pmod]" value="wf" title="iwf"></td>
                        <td>I have my own home page, <em>that mentions furries</em>, on the Web</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("j", "Anime (Japanese animation)", true, true, true, true, true); ?>
        
        <section>
            <p>Many of us are also avid fans of the genre of animation known as "Anime". You know, the kind where all the characters have huge eyes and even the bad guys are cute?</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="j[now]" value=""></th>
                        <th><input type="radio" name="j[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $anime = array(
                        "++++" => "I am a writer/artist/<em>seyeiuu</em>",
                        "+++"  => "I own every episode of every series ever made",
                        "++"   => "I watch it in <em>all</em> my spare time (furry fandom is, of course, not spare time)",
                        "+"    => "Others know I'm an Anime fan",
                        " "    => "Seen it, might think about seeing it again some time",
                        "-"    => "Haven't seen it, but would be interested when I get the time",
                        "--"   => "What's Anime?",
                        "---"  => "There should be a law against anything that cute",
                        "*"    => "I'll watch it, but <em>only</em> if it's all in English",
                    ); ?>
                    <? foreach ($anime as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="j[now]" value="<?=$k?>" title="j<?=$k?>"></td>
                            <td><input type="radio" name="j[fut]" value="<?=$k?>" title="j<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("p", "Pets", true, true, true, true, true); ?>
        
        <section>
            <p>If we can't be furries in Real Life (yet), at least we can do the next best thing and cohabit with them.</p>
            
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="p[now]" value=""></th>
                        <th><input type="radio" name="p[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $pets = array(
                        "+++"  => "I have a vast household of assorted furry/scaly/feathery creatures, and my life is organised for their benefit",
                        "++"   => "Several pets",
                        "+"    => "Two or three conventional pets (cats, dogs, etc), or one fairly exotic one",
                        " "    => "One pet of a fairly conventional type (cat, dog, etc)",
                        "-"    => "No pets currently, but I may enrich my life in the future",
                        "--"   => "I don't have any pets; my lifestyle/household/schedule is complicated enough already",
                        "---"  => "I wouldn't have the things in the house [are you sure you're a furry?]",
                        "*"    => "I'd like to have pets, but my landlord/parents/flatmates won't allow them",
                        "**"   => "Sorry, I'm allergic to animals",
                    ); ?>
                    <? foreach ($pets as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="p[now]" value="<?=$k?>" title="p<?=$k?>"></td>
                            <td><input type="radio" name="p[fut]" value="<?=$k?>" title="p<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="inner">
        <? head("s", "Human Sex", true, true, true, true, true); ?>
        
        <section>
            <p>Use this code, if you wish, to describe the sex (and sex life) of your human persona. As with the furry sex code, you should feel free to use the <em>#</em> ("mind your own business") or <em>!</em> ("nothing to do with me") modifiers if you prefer.</p>
            
            <p>First, which sex are you?</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="s[now][pmod]" value=""></th>
                        <th><input type="radio" name="s[fut][pmod]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sex = array(
                        "f"     => "Female",
                        "m"     => "Male",
                    ); ?>
                    <? foreach ($sex as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="s[now][pmod]" value="<?=$k?>" title="s<?=$k?>"></td>
                            <td><input type="radio" name="s[fut][pmod]" value="<?=$k?>" title="s<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            
            <p>If you wish to reveal any details of your sex life, use one of these codes:</p>
            <table class="codetbl">
                <thead>
                    <tr>
                        <th class="c_now">Now</th>
                        <th class="c_fut">Future<br><em>(Optional)</em></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><input type="radio" name="s[now]" value=""></th>
                        <th><input type="radio" name="s[fut]" value=""></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sexlife = array(
                        "+++"  => "There's more to life? Where is it, and what equipment do you need?",
                        "++"   => "I was once referred to as \"easy\", but I have no idea where that might have come from",
                        "+"    => "I've had real, live sex",
                        " "    => "I've had sex ... oh, you mean with someone else?",
                        "-"    => "Not having sex by choice",
                        "--"   => "Not having sex because I can't get any",
                        "---"  => "Not having sex because I'm a nun/priest",
                        "*"    => "I'm married, so I can get it whenever I want (well, that's the theory, anyway)",
                        "**"   => "I have a few little rug rats to prove I've been there (but with kids around, who has time for sex?)",
                    ); ?>
                    <? foreach ($sexlife as $k => $v) { ?>
                        <tr>
                            <td><input type="radio" name="s[now]" value="<?=$k?>" title="s<?=$k?>"></td>
                            <td><input type="radio" name="s[fut]" value="<?=$k?>" title="s<?=$k?>"></td>
                            <td><?=$v?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<div class="outer">
    <h1>Notes</h1>
    
    <div class="inner">
        <h2>Currently Unsupported Features</h2>
        <ul>
            <li>There's currently no way of giving multiple answers to a section, e.g. <em>FCF/FX</em> for "sometimes a fox; sometimes a lynx".</li>
            <li>One code cannot currently detail multiple characters (e.g. <em>FCWh4dm/CF3c/UGm6s</em>).</li>
            <li>No way of specifying multiple Real Life jobs/training.</li>
        </ul>
    </div>
    
    <div class="inner">
        <h2>To-Do List</h2>
        <ul>
            <li>Add a button to load in a code for modification.</li>
            <li>Allow unsure/approx/professional buttons to be selected separately for now &amp; future.</li>
        </ul>
    </div>
</div>

<footer>
    <table>
        <tbody>
            <tr>
                <td style="width:10px;white-space:nowrap;"><h1>Your Code:</h1></td>
                <td><textarea id="output" rows="2" cols="80"></textarea></td>
            </tr>
        </tbody>
    </table>
</footer>

<div style="position:fixed;bottom:0px;right:0px;font-size:0.6em;"><?=round(microtime(true) - $start, 4)?> s / <?=round(memory_get_peak_usage() / (1024*1024), 2)?> MiB</div>
</body>
</html>