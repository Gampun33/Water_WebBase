<?php
require_once __DIR__ . '/../logic/watermap_logic.php';  // โหลดไฟล์ logic ด้านหลัง (เช่น การดึงข้อมูลฐานข้อมูล และฟังก์ชันต่างๆ)
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div id="map-content">
  <img src="img4800/0123.png">
  <div id="map-overlay">
    <!-- dam_current icon -->
    <img src="img4800/dam_current.png" id="box-img"
      style="position: absolute; top: 430px; left: 1085px; width: 45px; z-index: 1000;" />

    <!-- dam icon -->
    <!-- ขวา -->
    <img src="img4800/reservoirs.png" id="box-img" class="dam"
      style=" top: 306px; left: 1090px; width: 40px; " />
    <img src="img4800/dam_small.png" id="box-img" class="dam"
      style=" top: 358px; left: 1080px; width: 53px; " />
    <img src="img4800/reservoirs.png" id="box-img" class="dam"
      style=" top: 184px; left: 1090px; width: 40px; " />
    <img src="img4800/dam_left.png" id="box-img" class="dam"
      style=" top: 417px; left: 1408px; width: 35px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 236px; left: 1150px; width: 33px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 305px; left: 1334px; width: 30px; " />
    <img src="img4800/dam_left.png" id="box-img" class="dam"
      style=" top: 407px; left: 1300px; width: 35px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 465px; left: 1300px; width: 35px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 490px; left: 1300px; width: 35px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 475px; left: 1265px; width: 35px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 535px; left: 1120px; width: 32px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 542px; left: 1205px; width: 32px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 566px; left: 1255px; width: 32px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 611px; left: 1216px; width: 32px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 695px; left: 1225px; width: 30px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 660px; left: 1235px; width: 30px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 675px; left: 1300px; width: 30px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 694px; left: 1223px; width: 31px; " />
    <img src="img4800/reservoir.png" id="box-img" class="dam"
      style=" top: 705px; left: 1195px; width: 35px; " />

    <!-- ซ้าย -->
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 711px; left: 945px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 746px; left: 946px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 746px; left: 805px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 780px; left: 1001px; width: 30px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 646px; left: 895px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 599px; left: 818px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 501px; left: 817px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 555px; left: 920px; width: 30px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 480px; left: 799px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 458px; left: 805px; width: 29px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 449px; left: 870px; width: 30px;" />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 400px; left: 785px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 377px; left: 807px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 351px; left: 808px; width: 31px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 329px; left: 816px; width: 32px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 305px; left: 816px; width: 32px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 373px; left: 1040px; width: 30px; " />
    <img src="img4800/reservoir_right.png" id="box-img" class="dam"
      style=" top: 391px; left: 1028px; width: 32px; " />

    <!-- yellow mark icon -->
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style="top: 404px; left: 1103px; width: 13px;" />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 265px; left: 1053px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 156px; left: 1103px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 278px; left: 1138px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 216px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 349px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 405px; left: 1103px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 420px; left: 1103px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 490px; left: 1103px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 685px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 702px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 798px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 820px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 845px; left: 1102px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 445px; left: 1053px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 417px; left: 745px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 483px; left: 890px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 576px; left: 1074px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 517px; left: 1144px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 517px; left: 1303px; width: 12px; " />
    <img src="img4800/mark.png" id="box-img" class="mark-icon"
      style=" top: 490px; left: 1419px; width: 12px; " />

    <!-- arrow -->
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-1" style="width: 85px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-2" style="width: 80px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-3" style="width: 45px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-4" style="width: 33px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-5" style="width: 33px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-6" style="width: 33px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-7" style="width: 30px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-8" style="width: 33px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-9" style="width: 31px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-10" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-11" style="width: 26px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-12" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-13" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-14" style="width: 38px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-15" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-16" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-17" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-18" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-19" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-20" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-21" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-22" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-23" style="width: 25px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-24" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-25" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-26" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-27" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-28" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-29" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-30" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-31" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-32" style="width: 38px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-33" style="width: 26px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-34" style="width: 33px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-35" style="width: 45px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-36" style="width: 45px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-37" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-38" style="width: 28px;">
    <img src="<?= $baseURL ?>/img4800/cursor.png" class="arrow-image" id="arrow-39" style="width: 28px;">


    <!-- weir icon -->
    <div class="weir weir-purple" style=" top: 108px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 119px;  left: 1093px;  "></div>
    <div class="weir weir-blue" style=" top: 130px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 141px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 470px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 506px;  left: 1093px; "></div>
    <div class="weir weir-blue" style=" top: 550px;  left: 1093px;  "></div>
    <div class="weir weir-blue" style=" top: 564px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 600px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 726px;  left: 1093px;  "></div>
    <div class="weir weir-purple" style=" top: 739px;  left: 1093px;   "></div>

    <div class="small-weir weir-purple" style=" top: 173px;  left: 957px;  z-index: 1000 !important; "></div>
    <div class="small-weir weir-purple" style=" top: 198px;  left: 957px;  z-index: 1000 !important; "></div>
    <div class="small-weir weir-blue" style=" top: 233px;  left: 957px;  z-index: 1000 !important; "></div>

    <div class="small-staing-weir weir-blue" style=" top: 244px;  left: 925px; z-index: 1000 !important;  "></div>
    <div class="tall-weir weir-purple" style=" top: 260px;  left: 1078px;  z-index: 1000 !important; "></div>
    <div class="tall-weir weir-blue" style=" top: 245px;  left: 915px;  z-index: 1000 !important; "></div>





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
      <div class="marker <?= $colorClass ?>" style="top: <?= $pos['name']['top'] ?>px; left: <?= $pos['name']['left'] ?>px; font-size: 9px;">
        <?= htmlspecialchars($name) ?>
      </div>
      <div class="marker <?= $colorClass ?>" style="top: <?= $pos['water']['top'] ?>px; left: <?= $pos['water']['left'] ?>px; font-size: 7px;">
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
      <div class="marker marker-dam" style="position: absolute; top: <?= $pos['top'] ?>px; left: <?= $pos['left'] ?>px; font-size : 7px; ">
        <?= formatFloatAtLeastTwoDecimals($current, 2) ?> mcm (<?= number_format($percent, 0) ?>%) <br>
        Inflow <?= formatFloatAtLeastTwoDecimals($inflow, 2) ?> mcm <br>
        Outflow <?= formatFloatAtLeastTwoDecimals($outflow, 2) ?> mcm
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
      <div class="rain-row" style="position: absolute; top: <?= $pos['top'] ?>px; left: <?= $pos['left'] ?>px; display: flex; gap: 5px; font-size: 12px;">
        <span class="rain-label" style="width: 170px;"><?= htmlspecialchars($row['location']) ?></span>
        <?php $class = getRainfallClass($row['rainfall_24h']); ?>
        <span class="rainfall-box <?= $class ?>" style="width: 50px; text-align: center;">
          <?= formatFloatAtLeastoneDecimals($row['rainfall_24h']) ?>
        </span>
        <span class="rain-72h" style="width: 60px; text-align: center; margin-left: 10px;">
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
            <svg width="70" height="50" viewBox="0 0 120 110" xmlns="http://www.w3.org/2000/svg">
              <polygon points="0,0 120,0 120,80 60,103 0,80" fill="#f0fff0" stroke="#4a3d00" stroke-width="4" />
              <defs>
                <clipPath id="clip-<?= $tank['id'] ?>">
                  <polygon points="0,0 120,0 120,80 60,103 0,80" />
                </clipPath>
              </defs>
              <rect x="0" y="<?= $y ?>" width="120" height="<?= $height ?>" fill="<?= $water_color ?>" clip-path="url(#clip-<?= $tank['id'] ?>)" />
              <text x="60" y="65" text-anchor="middle" fill="<?= $text_color ?>" font-weight="bold" font-size="18">
                <?= number_format($current, 2) ?> <tspan font-size="15">(<?= number_format($percent, 0) ?>%)</tspan>
              </text>
              <rect x="10" y="5" width="70" height="20" fill="lightblue" rx="3" ry="3" opacity="0.8" />
              <text x="43" y="20" text-anchor="middle" fill="black" font-size="18" font-weight="bold">
                <?= number_format(floatval($tank['water_level']), 2) ?>
              </text>
            </svg>
          </div>

          <!-- 🏷 Label ชื่อถัง -->
          <div class="label-water-name" style="position: absolute; top: <?= $labels['name_water']['top'] ?>px; left: <?= $labels['name_water']['left'] ?>px;
              font-size: 12px; max-width: 65px; text-align: center; white-space: nowrap; font-weight: bold;">
            <?= htmlspecialchars($tank['name_water']) ?>
          </div>

          <!-- 🏷 Label สถานที่ -->
          <div class="label-water-station" style="position: absolute; top: <?= $labels['name_location']['top'] ?>px; left: <?= $labels['name_location']['left'] ?>px;
              font-size: 11px; max-width: 70px; text-align: center; white-space: nowrap;">
            <?= htmlspecialchars($tank['name_location']) ?>
          </div>

          <!-- 🏷 Label พิกัด -->
          <div class="label-water-location" style="position: absolute; top: <?= $labels['location']['top'] ?>px; left: <?= $labels['location']['left'] ?>px;
              font-size: 11px; max-width: 50px; text-align: center; white-space: nowrap;">
            <?= htmlspecialchars($tank['location']) ?>
          </div>

          <?php if (isset($waterLevelPositions[$name])):
            $posLevel = $waterLevelPositions[$name];
          ?>
            <div class="water-level-current" style="position: absolute; top: <?= $posLevel['top'] ?>px; left: <?= $posLevel['left'] ?>px; font-size: 8px; color: #333;">
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
    <div class="station-box" style="position: absolute; top: <?= $totalBoxPositions['capacity']['top'] ?>px; left: <?= $totalBoxPositions['capacity']['left'] ?>px; font-size: 9px; color: white; font-weight: bold;">
      <?= number_format($total_capacity) ?>
    </div>

    <!-- 🔵 กล่องน้ำปัจจุบันรวม (Current Water) -->
    <div class="station-box" style="position: absolute; top: <?= $totalBoxPositions['current']['top'] ?>px; left: <?= $totalBoxPositions['current']['left'] ?>px; font-size: 9px; color: white; font-weight: bold;">
      <?= number_format($total_current_water) ?>
      (<?= number_format($total_current_percent, 0) ?>%)
    </div>

    <!-- 🟢 กล่องน้ำใช้การได้รวม (In Use Water) -->
    <div class="station-box" style="position: absolute; top: <?= $totalBoxPositions['inuse']['top'] ?>px; left: <?= $totalBoxPositions['inuse']['left'] ?>px; font-size: 9px; color: white; font-weight: bold;">
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

      <div class="marker mark-dam" style="top: <?= $pos['water']['top'] ?>px; left: <?= $pos['water']['left'] ?>px; ">
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
  <div class="d-flex align-items-center gap-2">
    <a class="btn btn-primary" href="test2.php">หน้าสร้าง PDF</a>
  </div>


</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gif.js/0.2.0/gif.min.js"></script>
<script src="assets/arrow_home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- โหลด gif.js ก่อนสคริปต์หลัก -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gif.js/0.2.0/gif.js"></script>



<script>
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


  flatpickr("#date", {
    dateFormat: "Y-m-d",
    maxDate: "<?= $approvedDate ?>", // ✅ จำกัดให้เลือกได้ถึงวันที่ approve ล่าสุด
    defaultDate: "<?= $selectedDate ?>", // ตั้งค่าเริ่มต้น
    allowInput: true
  });

  </script>