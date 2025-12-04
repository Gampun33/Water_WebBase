<?php
require_once __DIR__ . '/logic/test2_logic_1.php';  // โหลดไฟล์ logic ด้านหลัง (เช่น การดึงข้อมูลฐานข้อมูล และฟังก์ชันต่างๆ)
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>สร้างแผนที่น้ำ</title>
  <link rel="stylesheet" href="assets/test2.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">


</head>

<body>

  <div id="map-content">
    <img src="img4800/0123.png">
    <div id="map-overlay">
      <!-- dam_current icon -->
      <img src="img4800/dam_current.png" id="box-img"
        style="position: absolute; top: 1300px; left: 3190px; width: 110px; z-index: 1000;" />

      <!-- dam icon -->
      <!-- ขวา -->
      <img src="img4800/reservoirs.png" id="box-img" class="dam"
        style=" top: 540px; left: 3190px; width: 110px; " />
      <img src="img4800/dam_small.png" id="box-img" class="dam"
        style=" top: 1060px; left: 3190px; width: 110px; " />
      <img src="img4800/reservoirs.png" id="box-img" class="dam"
        style=" top: 900px; left: 3190px; width: 110px; " />
      <img src="img4800/dam_left.png" id="box-img" class="dam"
        style=" top: 1190px; left: 3800px; width: 110px; " />

      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 890px; left: 3900px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1370px; left:3815px; width: 90px; " />
      <img src="img4800/dam_left.png" id="box-img" class="dam"
        style=" top: 1220px; left: 4120px; width: 110px; " />

      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1440px; left: 3820px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1390px; left: 3720px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1570px; left: 3290px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1590px; left: 3530px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1790px; left: 3570px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1930px; left: 3610px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1970px; left: 3800px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 2060px; left: 3500px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 2030px; left: 3590px; width: 90px; " />
      <!-- หายังไม่เจอ -->
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 694px; left: 3370px; width: 90px; " />
      <img src="img4800/reservoir.png" id="box-img" class="dam"
        style=" top: 1662px; left: 3680px; width: 90px; " />


      <!-- ซ้าย -->
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 895px; left: 2390px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 963px; left: 2390px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1033px; left: 2370px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1103px; left: 2370px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1170px; left: 2300px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1315px; left: 2545px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1090px; left: 3045px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1148px; left: 3006px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1340px; left: 2350px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1408px; left: 2338px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1468px; left: 2387px; width: 90px;" />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1753px; left: 2395px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1625px; left: 2695px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 1895px; left: 2620px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 2082px; left: 2765px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 2188px; left: 2771px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 2188px; left: 2355px; width: 90px; " />
      <img src="img4800/reservoir_right.png" id="box-img" class="dam"
        style=" top: 2286px; left: 2935px; width: 90px; " />

      <!-- yellow mark icon -->
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style="top: 453px; left: 3235px; " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 635px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 775px; left: 3103px; " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 815px; left: 3320px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1018px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1190px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1230px; left: 3235px; " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1305px; left: 3098px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1440px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1515px; left: 3345px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1515px; left: 3840px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1440px; left: 4157px; " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1220px; left: 2180px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1689px; left: 3128px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 2055px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 2018px; left: 3235px;" />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 2330px; left: 3235px; " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 2400px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 2470px; left: 3235px;  " />
      <img src="img4800/mark.png" id="box-img" class="mark-icon"
        style=" top: 1415px; left: 2640px;  " />

      <!-- arrow -->
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-1" style="width: 200px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-2" style="width: 170px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-3" style="width: 100px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-4" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-5" style="width: 75px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-6" style="width: 100px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-7" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-8" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-9" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-10" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-11" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-12" style="width: 87px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-13" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-14" style="width: 100px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-15" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-16" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-17" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-18" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-19" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-20" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-21" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-22" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-23" style="width: 85px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-24" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-25" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-26" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-27" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-28" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-29" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-30" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-31" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-32" style="width: 100px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-33" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-34" style="width: 90px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-35" style="width: 120px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-36" style="width: 120px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-37" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-38" style="width: 80px;">
      <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-39" style="width: 80px;">


      <!-- weir icon -->
      <div class="weir weir-purple" style=" top: 315px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 350px;  left: 3198px;  "></div>
      <div class="weir weir-blue" style=" top: 385px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 415px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 1374px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 1760px;  left: 3198px; "></div>
      <div class="weir weir-blue" style=" top: 1488px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 1650px;  left: 3198px;  "></div>
      <div class="weir weir-blue" style=" top: 1615px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 2135px;  left: 3198px;  "></div>
      <div class="weir weir-purple" style=" top: 2170px;  left: 3198px;   "></div>

      <div class="small-weir weir-purple" style=" top: 508px;  left: 2807px;   "></div>
      <div class="small-weir weir-purple" style=" top: 573px;  left: 2807px;   "></div>
      <div class="small-weir weir-blue" style=" top: 683px;  left: 2807px;   "></div>

      <div class="tall-weir weir-blue" style=" top: 766px;  left: 3175px;   "></div>
      <div class="tall-weir weir-purple" style=" top: 722px;  left: 2678px;   "></div>





      <div class="date-label">วันที่ <?= formatThaiDate($selectedDate) ?></div>

      <?php foreach ($stations as $row): ?>
        <?php
        $name = $row['name'];
        $current = (float)$row['current_water'];
        $capacity = (float)$row['capacity'];
        $inflow = isset($row['inflow']) ? (float)$row['inflow'] : 0;
        $outflow = isset($row['outflow']) ? (float)$row['outflow'] : 0;
        $percent = ($capacity > 0) ? ($current / $capacity * 100) : 0;

        if (!isset($customStationPositions[$name])) continue;
        $pos = $customStationPositions[$name];

        $colorClass = ($percent > 80) ? 'text-red' : 'text-blue';
        ?>
        <div class="marker marker-info <?= $colorClass ?>" style="top: <?= $pos['name']['top'] ?>px; left: <?= $pos['name']['left'] ?>px; font-size: 22px;">
          <?= htmlspecialchars($name) ?>
        </div>
        <div class="marker marker-info <?= $colorClass ?>" style="top: <?= $pos['water']['top'] ?>px; left: <?= $pos['water']['left'] ?>px; font-size: 22px;">
          <?= number_format($current, 2) ?> (<?= number_format($percent, 0) ?>%)
          <span>
            <<?= number_format($inflow, 2) ?> / <?= number_format($outflow, 2) ?>>
          </span>
        </div>
      <?php endforeach; ?>

      <?php foreach ($stations as $row): ?>
        <?php
        $name = $row['name'];
        if (!isset($customReserviorPositions[$name])) continue;

        $pos = $customReserviorPositions[$name]['water'];
        $current = (float)$row['current_water'];
        $capacity = (float)$row['capacity'];
        $inflow = isset($row['inflow']) ? (float)$row['inflow'] : 0;
        $outflow = isset($row['outflow']) ? (float)$row['outflow'] : 0;
        $percent = ($capacity > 0) ? ($current / $capacity * 100) : 0;

        ?>
        <!-- แสดงค่าปริมาณน้ำ -->
        <div class="marker marker-dam" style="position: absolute; top: <?= $pos['top'] ?>px; left: <?= $pos['left'] ?>px; font-size : 23px; ">
          <?= formatFloatAtLeastTwoDecimals($current, 2) ?> mcm (<?= number_format($percent, 0) ?>%) <br>
          Inflow <?= formatFloatAtLeastTwoDecimals($inflow, 2) ?> mcm <br>
          Outflow <?= formatFloatAtLeastTwoDecimals($outflow, 2) ?> mcm
        </div>
      <?php endforeach; ?>

      <?php foreach ($stations as $row): ?>
        <?php
        $name = $row['name'];
        if (!isset($customStationPositions_1[$name])) continue;

        $posName = $customStationPositions_1[$name]['name'];   // ตำแหน่งชื่อ
        $posWater = $customStationPositions_1[$name]['water']; // ตำแหน่งน้ำ

        $current = (float)$row['current_water'];
        $capacity = (float)$row['capacity'];
        $percent = ($capacity > 0) ? ($current / $capacity * 100) : 0;
        ?>

        <!-- ชื่อสถานี -->
        <div class="marker marker-info" style="position: absolute; top: <?= $posName['top'] ?>px; left: <?= $posName['left'] ?>px; font-size: 18px; font-weight: bold;">
          <?= htmlspecialchars($name) ?>
        </div>

        <!-- ค่าปริมาณน้ำ -->
        <div class="marker marker-dam" style="position: absolute; top: <?= $posWater['top'] ?>px; left: <?= $posWater['left'] ?>px; font-size: 23px;">
          <?= formatFloatAtLeastTwoDecimals($current, 2) ?> mcm (<?= number_format($percent, 0) ?>%)
        </div>
      <?php endforeach; ?>



      <!-- กล่องสรุประดับน้ำต่างๆ ให้วางทับบนภาพ -->
      <div class="map-container">
        <?php foreach ($counts as $key => $value): ?>
          <?php if (isset($summaryPositions[$key])): ?>
            <div class="<?= $key === 'count_above_high' ? 'count-label-high' : 'count-label' ?>"
              style="top: <?= $summaryPositions[$key]['top'] ?>px; left: <?= $summaryPositions[$key]['left'] ?>px;">
              <?= $value ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <!-- ตารางฝน 24 ชั่วโมง และ 72 ชั่วโมง -->
      <?php foreach ($displayData as $index => $row): $pos = $rainTablePositions[$index]; ?>
        <div class="rain-row" style="position: absolute; top: <?= $pos['top'] ?>px; left: <?= $pos['left'] ?>px; display: flex; gap: 5px; font-size: 30px;">
          <span class="rain-label" style="width: 520px;"><?= htmlspecialchars($row['location']) ?></span>
          <?php $class = getRainfallClass($row['rainfall_24h']); ?>
          <span class="rainfall-box <?= $class ?>">
            <?= formatFloatAtLeastoneDecimals($row['rainfall_24h']) ?>
          </span>
          <span class="rain-72h" style="width: 80px; text-align: center; margin-left: 90px;">
            <?= formatFloatAtLeastoneDecimals($row['rainfall_72h']) ?>
          </span>
        </div>
      <?php endforeach; ?>

      <?php foreach ($selectedTankNames as $index => $name): ?>
        <?php if (isset($tankDataByName[$name])):
          $tank = $tankDataByName[$name];
          $current = $tank['water_current'];
          $capacity = $tank['capacity'];
          $percent = ($capacity > 0) ? ($current / $capacity * 100) : 0;
          $maxHeight = 103;
          $height = ($percent / 100) * $maxHeight;
          $height = ($percent > 0 && $height < 2) ? 2 : $height;
          $y = $maxHeight - $height;
          $text_color = $percent > 100 ? 'white' : 'black';
          $water_color = getWaterColor($percent);
          $pos = $tankPositions[$index] ?? ['x' => 0, 'y' => 0];
        ?>
          <?php foreach ($selectedTankNames as $index => $name): ?>
            <?php
            if (!isset($tankDataByName[$name], $tankPositions[$index], $tankLabelPositions[$name])) continue;

            $tank = $tankDataByName[$name];
            $pos = $tankPositions[$index];
            $labels = $tankLabelPositions[$name];

            $capacity = $tank['capacity'];
            $current = $tank['water_current'];
            $percent = ($capacity > 0) ? ($current / $capacity * 100) : 0;
            $height = max(2, ($percent / 100) * 103);
            $y = 103 - $height;
            $water_color = getWaterColor($percent);
            $text_color = $percent > 100 ? 'white' : 'black';
            ?>

            <!-- 🔵 SVG ถังน้ำ -->
            <div class="tank-item" style="position: absolute; left: <?= $pos['x'] ?>px; top: <?= $pos['y'] ?>px;">
              <div class="top-label"><?= number_format($capacity, 2) ?> cms</div>
              <svg width="180" height="160" viewBox="0 0 120 110" xmlns="http://www.w3.org/2000/svg">
                <polygon points="0,0 120,0 120,80 60,103 0,80" fill="#f0fff0" stroke="#4a3d00" stroke-width="4" />
                <defs>
                  <clipPath id="clip-<?= $tank['id'] ?>">
                    <polygon points="0,0 120,0 120,80 60,103 0,80" />
                  </clipPath>
                </defs>
                <rect x="0" y="<?= $y ?>" width="120" height="<?= $height ?>" fill="<?= $water_color ?>" clip-path="url(#clip-<?= $tank['id'] ?>)" />
                <text x="60" y="65" text-anchor="middle" fill="<?= $text_color ?>" font-weight="bold" font-size="15">
                  <?= number_format($current, 2) ?> <tspan font-size="15">(<?= number_format($percent, 0) ?>%)</tspan>
                </text>
                <rect x="10" y="5" width="70" height="20" fill="lightblue" rx="3" ry="3" opacity="0.8" />
                <text x="43" y="20" text-anchor="middle" fill="black" font-size="15" font-weight="bold">
                  <?= number_format(floatval($tank['water_level']), 2) ?>
                </text>
              </svg>
            </div>

            <!-- 🏷 Label ชื่อถัง -->
            <div class="label-water-name" style="position: absolute; top: <?= $labels['name_water']['top'] ?>px; left: <?= $labels['name_water']['left'] ?>px;
              font-size: 30px; max-width: 65px; text-align: center; white-space: nowrap; font-weight: bold;">
              <?= htmlspecialchars($tank['name_water']) ?>
            </div>

            <!-- 🏷 Label สถานที่ -->
            <div class="label-water-station" style="position: absolute; top: <?= $labels['name_location']['top'] ?>px; left: <?= $labels['name_location']['left'] ?>px;
              font-size: 20px; max-width: 70px; text-align: center; white-space: nowrap;">
              <?= htmlspecialchars($tank['name_location']) ?>
            </div>

            <!-- 🏷 Label พิกัด -->
            <div class="label-water-location" style="position: absolute; top: <?= $labels['location']['top'] ?>px; left: <?= $labels['location']['left'] ?>px;
              font-size: 20px; max-width: 50px; text-align: center; white-space: nowrap;">
              <?= htmlspecialchars($tank['location']) ?>
            </div>

            <?php if (isset($waterLevelPositions[$name])):
              $posLevel = $waterLevelPositions[$name];
            ?>
              <div class="water-level-current" style="position: absolute; top: <?= $posLevel['top'] ?>px; left: <?= $posLevel['left'] ?>px; font-size: 18px; color: #333;">
                <?= number_format($tank['water_level_current'], 2) ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      <?php endforeach; ?>

      <div class="station-data-row" style="position: relative; width: 100%; ">
        <?php foreach ($rows as $row):
          $name = $row['name_data'];
          if (!isset($dataBoxPositions[$name])) continue;
          $pos = $dataBoxPositions[$name];

          $cap = floatval($row['capacity']);
          $current = floatval($row['current_water']);
          $inuse = floatval($row['water_inuse']);

          $currentPercent = ($cap > 0) ? ($current / $cap * 100) : 0;
          $inusePercent = ($cap > 0) ? ($inuse / $cap * 100) : 0;
        ?>
          <div class="station-box" style="position: absolute; top: <?= $pos['top'] ?>px; left: <?= $pos['left'] ?>px;">
            <div class="data-row">
              <span class="col-cap"><?= number_format($cap) ?></span>
              <span class="col-current" style="color: white;">
                <?= number_format($current) ?> (<?= number_format($currentPercent, 0) ?>%)
              </span>
              <span class="col-inuse">
                <?= number_format($inuse) ?> (<?= number_format($inusePercent, 0) ?>%)
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- กล่องที่ 4: แสดงรวม -->
      <div class="station-box" style="position: absolute; top: <?= $totalBoxPositions['capacity']['top'] ?>px; left: <?= $totalBoxPositions['capacity']['left'] ?>px;  color: white; font-weight: bold;">
        <?= number_format($total_capacity) ?>
      </div>

      <!-- 🔵 กล่องน้ำปัจจุบันรวม (Current Water) -->
      <div class="station-box" style="position: absolute; top: <?= $totalBoxPositions['current']['top'] ?>px; left: <?= $totalBoxPositions['current']['left'] ?>px;  color: white; font-weight: bold;">
        <?= number_format($total_current_water) ?>
        (<?= number_format($total_current_percent, 0) ?>%)
      </div>

      <!-- 🟢 กล่องน้ำใช้การได้รวม (In Use Water) -->
      <div class="station-box" style="position: absolute; top: <?= $totalBoxPositions['inuse']['top'] ?>px; left: <?= $totalBoxPositions['inuse']['left'] ?>px;  color: white; font-weight: bold;">
        <?= number_format($total_inuse) ?>
        (<?= number_format($total_inuse_percent, 0) ?>%)
      </div>

      <?php foreach ($allWaterData as $row):
        $name = $row['name_water']; // สำคัญ! ใช้สำหรับ map ตำแหน่ง
        $current = $row['water_current'];
        $level = $row['water_level_current'];

        if (!isset($customStationwater[$name])) continue;
        $pos = $customStationwater[$name];
      ?>

        <div class="marker mark_dam" style="top: <?= $pos['water']['top'] ?>px; left: <?= $pos['water']['left'] ?>px; font-size :20px;">
          <?= number_format($current, 2) ?> cms
          <span>(<?= number_format($level, 2) ?> m)</span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="no-print d-flex flex-column align-items-center mt-4 gap-3">
    <form method="get" class="d-flex flex-wrap justify-content-center align-items-center gap-2">
      <input type="hidden" name="page" value="home">
      <label for="date">เลือกวันที่:</label>
      <input type="text" name="date" id="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-control flatpickr" style="width: auto;">
      <button type="submit" class="btn btn-primary">🔄 โหลดใหม่</button>
    </form>


    <div class="d-flex align-items-center gap-3">
      <button id="create-gif-btn" class="btn btn-primary">สร้าง GIF</button>
      <div id="gif-status"></div>
      <div id="gif-result"></div>
      <button id="create-pdf-btn" class="btn btn-success">สร้าง PDF</button>
      <div id="pdf-status"></div>
      <a class="btn btn-warning" href="index.php?page=home">กลับหน้าหลัก</a>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gif.js/0.2.0/gif.min.js"></script>
  <script src="assets/arrow_test2.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- โหลด gif.js ก่อนสคริปต์หลัก -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gif.js/0.2.0/gif.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



  <script>
    flatpickr("#date", {
      dateFormat: "Y-m-d",
      maxDate: "<?= $approvedDate ?>", // ✅ จำกัดให้เลือกได้ถึงวันที่ approve ล่าสุด
      defaultDate: "<?= $selectedDate ?>", // ตั้งค่าเริ่มต้น
    });

    let scale = 1;
    const mapContent = document.getElementById('map-content'); // ✅ ตรงนี้พอแล้ว ใช้ซ้ำได้เลย

    window.addEventListener('wheel', function(e) {
      if (e.ctrlKey) {
        e.preventDefault();

        const zoomStep = 0.05;
        const delta = e.deltaY > 0 ? -zoomStep : zoomStep;

        scale = Math.min(Math.max(0.5, scale + delta), 3);
        mapContent.style.transform = `scale(${scale})`;
      }
    }, {
      passive: false
    });


    const gifStatus = document.getElementById("gif-status");
    const gifResult = document.getElementById("gif-result");

    document.getElementById("create-gif-btn").addEventListener("click", async () => {
      try {
        gifStatus.textContent = "📸 กำลังจับภาพแผนที่...";
        gifResult.innerHTML = "";

        const gif = new GIF({
          workers: 3,
          quality: 5,
          workerScript: "assets/gif.worker.js",
          transparent: null,
          dither: false,
        });

        const totalFrames = 30;
        const delay = 100;

        for (let i = 0; i < totalFrames; i++) {
          const percent = Math.round(((i + 1) / totalFrames) * 100);
          gifStatus.textContent = `📸 กำลังจับภาพแผนที่... ${percent}% (${i + 1}/${totalFrames})`;

          const canvas = await html2canvas(mapContent, {
            scale: 2
          });
          gif.addFrame(canvas, {
            delay,
            copy: true
          });

          await new Promise(r => setTimeout(r, 150));
        }

        gif.on("progress", (p) => {
          const percent = Math.round(p * 100);
          gifStatus.textContent = `🛠️ กำลังเรนเดอร์ GIF... ${percent}%`;
        });

        gif.on("finished", (blob) => {
          gifStatus.textContent = "✅ สร้าง GIF เสร็จแล้ว กำลังดาวน์โหลดไฟล์...";

          const url = URL.createObjectURL(blob);

          const a = document.createElement("a");
          a.href = url;
          a.download = "map-animation.gif";
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);

          setTimeout(() => URL.revokeObjectURL(url), 1000);
        });

        gif.render();
      } catch (err) {
        gifStatus.textContent = "❌ เกิดข้อผิดพลาดในการสร้าง GIF";
        console.error(err);
      }
    });


    // ======================
    // ปุ่มสร้าง PDF
    // ======================
    document.getElementById("create-pdf-btn").addEventListener("click", async () => {
      const pdfStatus = document.getElementById("pdf-status");
      const mapContent = document.getElementById("map-content");

      try {
        pdfStatus.textContent = "📸 กำลังเตรียมแผนที่...";

        stopArrowsAnimation();

        const overlaysToHide = document.querySelectorAll('.arrow-image, .mark-icon');
        overlaysToHide.forEach(el => {
          el.style.setProperty('display', 'none', 'important');
          el.style.setProperty('visibility', 'hidden', 'important');
        });

        await new Promise(resolve => setTimeout(resolve, 100));

        const bgImage = document.querySelector('#map-content img');
        const originalSrc = bgImage?.src;

        const targetSrc = window.location.origin + '/img4800/1.png';

        if (bgImage) {
          if (bgImage.src !== targetSrc) {
            await new Promise((resolve, reject) => {
              bgImage.onload = () => resolve();
              bgImage.onerror = () => reject("โหลดภาพพื้นหลังไม่สำเร็จ");
              bgImage.src = 'img4800/1.png';
            });
          } else {
            await new Promise(resolve => setTimeout(resolve, 100));
          }
        }

        // ✅ รอให้ภาพทั้งหมดโหลดก่อน
        await waitForImagesToLoad(mapContent);

        const {
          jsPDF
        } = window.jspdf;
        const canvas = await html2canvas(mapContent, {
          scale: 2
        });
        const imgData = canvas.toDataURL("image/png");

        const pdf = new jsPDF({
          orientation: "landscape",
          unit: "pt",
          format: [4500, 2658],
        });

        pdf.addImage(imgData, "PNG", 0, 0, 4500, 2658);
        pdfStatus.textContent = "✅ สร้าง PDF เสร็จแล้ว กำลังดาวน์โหลดไฟล์...";
        pdf.save("map-content.pdf");
        setTimeout(() => {
          location.reload();
        }, 1000); // รอ 1 วินาที

        // คืนค่ารูป
        if (bgImage && originalSrc) bgImage.src = originalSrc;

        overlaysToHide.forEach(el => {
          el.style.removeProperty('display');
          el.style.removeProperty('visibility');
        });

        startArrowsAnimation();

      } catch (error) {
        pdfStatus.textContent = "❌ เกิดข้อผิดพลาดในการสร้าง PDF";
        console.error(error);
      }
    });

    function waitForImagesToLoad(container) {
      const images = container.querySelectorAll("img");
      const promises = Array.from(images).map(img => {
        if (img.complete) return Promise.resolve();
        return new Promise(resolve => {
          img.onload = img.onerror = resolve;
        });
      });
      return Promise.all(promises);
    }
  </script>

</body>

</html>