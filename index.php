<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body style='overflow: hidden; background-color: #FFFFFF;'>
        <div style='text-align: center; top: 0px; left: 0px; right: 0px; height: 50px; overflow: show; font-family: Arial; font-size: 24px; font-weight: bold; color: #000' id='infos'>
        </div>

        <div style='text-align: center; top: 40px; left: 0px; right: 0px; height: 50px; overflow: show; font-family: Arial; font-size: 24px; font-weight: bold; color: #888' id='files'>
        </div>

        <div style='top: 100px; margin: 0 auto; overflow: hidden;' id='icons_div'>
          <div style='position: relative; float: right; right: 50%;'>
                <div style='position: relative; float: right; right: -50%;' id='icons'>
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <div style='padding-top: 30px; top: 0; right: 0; bottom: 0; left: 0; width: 50%; height: 50%; margin: auto; overflow: show;'>
                <img src='loading.png' id='teste'>
                <div id='loadingtext' style='margin-top: 10px; color: #666; font-family: Arial; font-size: 12px; font-weight: bold;'>Waiting for updates...</div>
            </div>
        </div>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>

        <script type="text/javascript">
            
            //  ------------------------------------- //
                // Number of messages that we can see bellow the GMod logo 
                var MESSAGES = 18;
                // Random messages will appear after this time, but it allways goes back to 0 when a new download starts
                var SECONDS_FOR_RANDOM_MESSAGES = 15;
                // Plays audio when a download starts
                var AUDIO = true;
                // Icons Width x Height
                var ICONS_WIDTH = 16;
                var ICONS_HEIGHT = 16;

                // Animations:

                // Floating icons
                var ICONS_RUNNER = true;

                // Icons poping in a box
                var ICONS_BOX = false;
                    // Number of icons per line
                    var ICONS_PER_LINE = 30;
                    // Number of lines
                    var LINES = 7;

            //  ------------------------------------- //

            var keywords = [
                "Unwelding Boxes", 
                "Charging Toolgun", 
                "Breaking Addons", 
                "Stuffing Ragdolls", 
                "Loading JBMod", 
                "Refuelling Thrusters", 
                "Unknotting ropes",
                "Painting Barrels",
                "Feeding Birds",
                "Bathing Father Grigori",
                "Decoding Lua's syntax",
                "Re-killing Alyx",
                "Calibrating Manhacks",
                "Cleaning Leafblower",
                "Reconfiguring Gravity Matrix",
                "Growing Watermelons",
                "Mowing Grass",
                "Plastering Walls",
                "Inflating Balloons",
                "Awaiting City17's orders",
                "Calling Sleep( 1000 );",
                "Unfreezing The Freeman",
                "Patching Broken Update",
                "Styling Mossman's Hair",
                "Reducing lifespan of G-Man",
                "Polishing Kleiner's Head",
                "Delaying Half-Life 3",
                "Changing Physgun Batteries",
                "Breaking Source Engine"
            ]
            
            var link = "icons/";

            var ext = [];
            
            ext['png'] = "picture.png";
            ext['vtf'] = "picture.png";
            ext['vmt'] = "page_white_picture.png";
            
            ext['wav'] = "sound.png";
            ext['mp3'] = "sound.png";
            ext['ogg'] = "sound.png";
            
            ext['txt'] = "page_white_text.png";
            ext['htm'] = "page_white_world.png";
            ext['tml'] = "page_white_world.png"; // html
            
            ext['bsp'] = "world.png";
            ext['ain'] = "world_add.png";
            
            ext['ttf'] = "font.png";
            ext['otf'] = "font.png";

            ext['mdl'] = "brick.png";            
            ext['vvd'] = "brick_add.png";
            ext['vtx'] = "brick_add.png";
            ext['phy'] = "brick_add.png";

            ext['.db'] = "database.png";

            ext['wlo'] = "package.png"; // Fake extension for Workshop Loading
            ext['wdo'] = "package_go.png"; // Fake extension for Workshop Downloading

            ext['generic'] = "box.png";

            var audio = [];
            audio[1] = new Audio('push.wav');
            audio[2] = new Audio('push.wav');
            audio[3] = new Audio('push.wav');
            audio[4] = new Audio('push.wav');
            audio[5] = new Audio('push.wav');
            audio[6] = new Audio('push.wav');
            var audioIncrement = 1;

            var msg = [];
            for (i=1; i<=MESSAGES; i++)
                msg[i] = "";

            var time = 0;

            var iconArray = "";
            var iconIncrement = 0;

            var FilesNeeded = 0;
            var FilesTotal = 0;
            var DownloadingWorkshop = true;

            // ----------------------------------------------------------------------------------
            // Icons Box ------------------------------------------------------------------------

            if (ICONS_BOX) {
                document.getElementById("icons_div").style.height = ICONS_HEIGHT * LINES + LINES + 'px';
                document.getElementById("icons_div").style.width = ICONS_WIDTH * ICONS_PER_LINE + ICONS_PER_LINE * 2 + 'px';

                function Right(str, n) {
                    if (n <= 0)
                    return "";
                    else if (n > String(str).length)
                    return str;
                    else {
                    var iLen = String(str).length;
                    return String(str).substring(iLen, iLen - n);
                    }
                }

                function FileListing(filename) {
                    var icon = "icon" + iconIncrement;
                    
                    if (Right(filename, 3) in ext)
                        iconArray = '<img id="' + icon + '" style="float: left; width: ' + ICONS_WIDTH + 'px; height: ' + ICONS_HEIGHT + 'px; padding: 1px 2px 0 0;" src="' + link + ext[Right(filename, 3)] + '"/>' + iconArray;
                    else
                        iconArray = '<img id="' + icon + '" style="float: left; width: ' + ICONS_WIDTH + 'px; height: ' + ICONS_HEIGHT + 'px; padding: 1px 2px 0 0;" src="' + link + ext["generic"] + '"/>' + iconArray;

                    document.getElementById("icons").innerHTML = iconArray;

                    document.getElementById(icon).style.height = '0px';
                    document.getElementById(icon).style.width = '0px';
                    
                    $("#" + icon).animate({height: ICONS_HEIGHT + "px", width: ICONS_WIDTH + "px"});
                    
                    iconIncrement++;
                    
                    if (AUDIO) {
                        audio[audioIncrement].play();

                        if (audioIncrement == 6)
                            audioIncrement = 0;

                        audioIncrement++;
                    }
                }
            }

            // ----------------------------------------------------------------------------------
            // Icons Runner ---------------------------------------------------------------------

            if (ICONS_RUNNER) {
                function FileListing(filename) {
                    
                }
            }

            // ----------------------------------------------------------------------------------
            // Change Text ----------------------------------------------------------------------

            function UpdateText(text) {
                var str = "";
                var i;
                
                for (i=MESSAGES; i>=2; i--)
                    msg[i] = msg[i-1];

                msg[1] = text;

                for (i=1; i<=MESSAGES; i++)
                    str = str + '<span style="color: ' + 'hsl(0, 0%,' + 100*(1-(1/MESSAGES)*(MESSAGES-i)) + '%)' + ';">' + msg[i] + '</span><br/>';
                
                document.getElementById("loadingtext").innerHTML = str;
            }

            function ChangeText() {
                setTimeout(function () {
                    time++;

                    if (time > SECONDS_FOR_RANDOM_MESSAGES) {
                        var keyword = keywords[Math.floor(Math.random() * keywords.length)]
                        UpdateText(keyword);
                        time = 0;
                    }

                    ChangeText();
                }, 1000)
            }

            function RefreshFileBox(text) {
                if (FilesNeeded != 0)
                    document.getElementById("files").innerHTML = text || FilesNeeded + " files needed"; 
            }

            // ----------------------------------------------------------------------------------
            // GMod Functions -------------------------------------------------------------------

            function GameDetails(servername, serverurl, mapname, maxplayers, steamid, gamemode) {
                document.getElementById("infos").innerHTML = servername;
            }

            function SetFilesNeeded(Needed) {
                FilesNeeded = Needed;
                RefreshFileBox();
            }

            function SetFilesTotal(iTotal) {
                FilesTotal = iTotal;
                RefreshFileBox();
            }

            function DownloadingFile(filename) {
                FilesNeeded--;

                if (FilesNeeded == 0 || isNaN(FilesNeeded))
                        FilesNeeded = "( ͡° ͜ʖ ͡°) 0";

                RefreshFileBox();

                UpdateText("Downloading " + filename);

                time = 0;

                if (DownloadingWorkshop)
                    FileListing(".wdo");
                else
                    FileListing(filename);
            }

            function SetStatusChanged(status) {
                if (status == "Mounting Addons")
                    DownloadingWorkshop = false;
 
                 if (status == "Received all Lua files we needed!")
                    RefreshFileBox(keywords[Math.floor(Math.random() * keywords.length)])

                if (DownloadingWorkshop) {
                    FilesNeeded--;

                    if (FilesNeeded == 0 || isNaN(FilesNeeded))
                        FilesNeeded = "( ͡° ͜ʖ ͡°) 0";

                    RefreshFileBox();
                    
                    FileListing(".wlo");

                    status = status.replace(/\d.*?\-/g, ''); // Remove the counting and total size
                }

                UpdateText(status);
                time = 0;
            }

            RefreshFileBox();
            ChangeText();
        </script>
    </body>
</html>
