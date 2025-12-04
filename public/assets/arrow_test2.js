const arrows = [
  // ตัวอย่าง config ลูกศรเหมือนเดิม
  {
    id: 'arrow-1',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2740, y: 2470 },
        end: { x: 3000, y: 2500 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 3075, y: 2550 },
        end: { x: 3075, y: 2590 },
        duration: 700
      }
    ]
  },
  {
    id: 'arrow-2',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 3245, y: 240 },
        end: { x: 3245, y: 2520 },
        duration: 9000
      },
      {
        type: 'line',
        start: { x: 3200, y: 2565 },
        end: { x: 3145, y: 2565 },
        duration: 1000
      }
    ]
  },
  {
    id: 'arrow-3',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2837, y: 480 },
        end: { x: 2837, y: 760 },
        duration: 4000
      },
      {
        type: 'line',
        start: { x: 2880, y: 793 },
        end: { x: 3200, y: 793 },
        duration: 4000
      }
    ]
  },
  {
    id: 'arrow-4',
    type: 'line',
    start: { x: 4000, y: 715 },
    end: { x: 3290, y: 715 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-5',
    type: 'line',
    start: { x: 3380, y: 827 },
    end: { x: 3290, y: 827 },
    duration: 4000,
    rotationOffset: 0
  },
  {
    id: 'arrow-6',
    type: 'line',
    start: { x: 4060, y: 1810 },
    end: { x: 3290, y: 1810 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-7',
    type: 'line',
    start: { x: 3870, y: 1952 },
    end: { x: 3290, y: 1952 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-8',
    type: 'line',
    start: { x: 3950, y: 1992 },
    end: { x: 3290, y: 1992 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-9',
    type: 'line',
    start: { x: 2280, y: 2217 },
    end: { x: 3200, y: 2217 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-10',
    type: 'line',
    start: { x: 2670, y: 2111 },
    end: { x: 3200, y: 2111 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-11',
    type: 'line',
    start: { x: 2910, y: 2312 },
    end: { x: 3200, y: 2312 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-12',
    type: 'line',
    start: { x: 2300, y: 1846 },
    end: { x: 2635, y: 1846 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-13',
    type: 'line',
    start: { x: 2370, y: 1783 },
    end: { x: 2635, y: 1783 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-14',
    type: 'line',
    start: { x: 2240, y: 1432 },
    end: { x: 3200, y: 1432 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-15',
    type: 'line',
    start: { x: 2250, y: 1200 },
    end: { x: 2770, y: 1200 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-16',
    type: 'line',
    start: { x: 2290, y: 1132 },
    end: { x: 2770, y: 1132 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-17',
    type: 'line',
    start: { x: 2310, y: 1061 },
    end: { x: 2770, y: 1061 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-18',
    type: 'line',
    start: { x: 2370, y: 991 },
    end: { x: 2770, y: 991 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-19',
    type: 'line',
    start: { x: 2390, y: 925 },
    end: { x: 2770, y: 925 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-20',
    type: 'line',
    start: { x: 2460, y: 749 },
    end: { x: 2815, y: 749 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-21',
    type: 'line',
    start: { x: 3753, y: 1370 },
    end: { x: 3753, y: 1500 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-22',
    type: 'line',
    start: { x: 3855, y: 1180 },
    end: { x: 3855, y: 1500 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-23',
    type: 'line',
    start: { x: 3400, y: 1600 },
    end: { x: 3280, y: 1600 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-24',
    type: 'line',
    start: { x: 3575, y: 1630 },
    end: { x: 3575, y: 1560 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-25',
    type: 'line',
    start: { x: 3723, y: 1730 },
    end: { x: 3723, y: 1560 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-26',
    type: 'line',
    start: { x: 3545, y: 2100 },
    end: { x: 3545, y: 2020 },
    duration: 3500,
    rotationOffset: 0
  },
  {
    id: 'arrow-27',
    type: 'line',
    start: { x: 3630, y: 2070 },
    end: { x: 3630, y: 2020 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-28',
    type: 'line',
    start: { x: 2735, y: 1570 },
    end: { x: 2735, y: 1680 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-29',
    type: 'line',
    start: { x: 2970, y: 1174 },
    end: { x: 3200, y: 1174 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-30',
    type: 'line',
    start: { x: 3020, y: 1117 },
    end: { x: 3200, y: 1117 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-31',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2965, y: 480 },
        end: { x: 2965, y: 600 },
        duration: 2500
      },
      {
        type: 'line',
        start: { x: 2920, y: 660 },
        end: { x: 2870, y: 660 },
        duration: 2000
      }
    ]
  },
  {
    id: 'arrow-32',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2420, y: 863 },
        end: { x: 2730, y: 863 },
        duration: 2500
      },
      {
        type: 'line',
        start: { x: 2800, y: 890 },
        end: { x: 2800, y: 1280 },
        duration: 4000
      },
      {
        type: 'line',
        start: { x: 2860, y: 1323 },
        end: { x: 3200, y: 1323 },
        duration: 3000
      }
    ]
  },
  {
    id: 'arrow-33',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2130, y: 1238 },
        end: { x: 2700, y: 1238 },
        duration: 4000
      },
      {
        type: 'line',
        start: { x: 2756, y: 1280 },
        end: { x: 2756, y: 1340 },
        duration: 1500
      },
      {
        type: 'line',
        start: { x: 2800, y: 1380 },
        end: { x: 3100, y: 1380 },
        duration: 1500
      },
      {
        type: 'line',
        start: { x: 3144, y: 1405 },
        end: { x: 3144, y: 1410 },
        duration: 500
      }
    ]
  },
  {
    id: 'arrow-34',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 3946, y: 870 },
        end: { x: 3946, y: 940 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 3900, y: 1002 },
        end: { x: 3290, y: 1002 },
        duration: 4000
      }
    ]
  },
  {
    id: 'arrow-35',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 4170, y: 1150 },
        end: { x: 4170, y: 1500 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 4140, y: 1528 },
        end: { x: 3290, y: 1528 },
        duration: 5000
      }
    ]
  },
  {
    id: 'arrow-36',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2665, y: 1980 },
        end: { x: 2665, y: 1760 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 2695, y: 1708 },
        end: { x: 3200, y: 1708 },
        duration: 5000
      }
    ]
  },
  {
    id: 'arrow-37',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2250, y: 1279 },
        end: { x: 2550, y: 1279 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 2585, y: 1320 },
        end: { x: 2585, y: 1400 },
        duration: 3000
      }
    ]
  },
  {
    id: 'arrow-38',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2350, y: 1367 },
        end: { x: 2470, y: 1367 },
        duration: 2500
      },
      {
        type: 'line',
        start: { x: 2511, y: 1395 },
        end: { x: 2511, y: 1405 },
        duration: 800
      }
    ]
  },
  {
    id: 'arrow-39',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 2370, y: 1495 },
        end: { x: 2520, y: 1495 },
        duration: 3000
      },
      {
        type: 'line',
        start: { x: 2560, y: 1460 },
        end: { x: 2560, y: 1455 },
        duration: 500
      }
    ]
  }
];

// เก็บ id ของ requestAnimationFrame เพื่อหยุด animation ได้
const animationFrames = {};
const animationStates = {}; // เก็บสถานะเพิ่มเติม เช่น currentSegment, startTime, segmentIndex

function getAngle(x1, y1, x2, y2) {
  return Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;
}

function getQuadraticBezierXY(t, p0, cp, p1) {
  const x = (1 - t) ** 2 * p0.x + 2 * (1 - t) * t * cp.x + t ** 2 * p1.x;
  const y = (1 - t) ** 2 * p0.y + 2 * (1 - t) * t * cp.y + t ** 2 * p1.y;
  return { x, y };
}

function animateArrow(arrowConfig) {
  const arrow = document.getElementById(arrowConfig.id);
  if (!arrow) return;

  // รีเซ็ตสถานะ animation
  animationStates[arrowConfig.id] = {
    currentSegment: 0,
    segmentIndex: 0,
    startTime: null,
    stopped: false
  };

  function step(time) {
    if (animationStates[arrowConfig.id].stopped) {
      // หยุด animation
      arrow.style.display = 'none';
      return;
    }

    if (!animationStates[arrowConfig.id].startTime) animationStates[arrowConfig.id].startTime = time;

    const state = animationStates[arrowConfig.id];

    // animation แบบ combo (หลาย segment)
    if (arrowConfig.type === 'combo') {
      const segment = arrowConfig.segments[state.currentSegment];
      const duration = segment.duration || 3000;
      const elapsed = time - state.startTime;
      const t = Math.min(elapsed / duration, 1);

      let x, y, angle;

      if (segment.type === 'line') {
        const { start, end } = segment;
        x = start.x + (end.x - start.x) * t;
        y = start.y + (end.y - start.y) * t;
        angle = getAngle(start.x, start.y, end.x, end.y) + (arrowConfig.rotationOffset || 0);
      } else if (segment.type === 'curve') {
        const { p0, cp, p1 } = segment.curve;
        const pos = getQuadraticBezierXY(t, p0, cp, p1);
        const ahead = getQuadraticBezierXY(Math.min(t + 0.01, 1), p0, cp, p1);
        x = pos.x;
        y = pos.y;
        angle = getAngle(pos.x, pos.y, ahead.x, ahead.y) + (arrowConfig.rotationOffset || 0);
      }

      arrow.style.display = 'block';
      arrow.style.left = x + 'px';
      arrow.style.top = y + 'px';
      arrow.style.transform = `translate(-50%, -50%) rotate(${angle}deg)`;

      if (t < 1) {
        animationFrames[arrowConfig.id] = requestAnimationFrame(step);
      } else {
        state.currentSegment++;
        if (state.currentSegment < arrowConfig.segments.length) {
          state.startTime = null;
          animationFrames[arrowConfig.id] = requestAnimationFrame(step);
        } else {
          // animation จบ ให้ซ่อนและเริ่มใหม่ช้าๆ
          arrow.style.display = 'none';
          setTimeout(() => {
            state.currentSegment = 0;
            state.startTime = null;
            if (!state.stopped) animationFrames[arrowConfig.id] = requestAnimationFrame(step);
          }, 500);
        }
      }
      return;
    }

    // กรณีอื่น (curve, line, waypoints)
    const duration = arrowConfig.durationPerSegment || arrowConfig.duration || 3000;
    const elapsed = time - animationStates[arrowConfig.id].startTime;
    const progress = Math.min(elapsed / duration, 1);

    let x, y, angle;
    const hasWaypoints = Array.isArray(arrowConfig.waypoints) && arrowConfig.waypoints.length > 1;
    const isCurve = arrowConfig.type === 'curve';
    const state2 = animationStates[arrowConfig.id];

    if (isCurve) {
      const { p0, cp, p1 } = arrowConfig.curve;
      const pos = getQuadraticBezierXY(progress, p0, cp, p1);
      const ahead = getQuadraticBezierXY(Math.min(progress + 0.01, 1), p0, cp, p1);
      x = pos.x;
      y = pos.y;
      angle = getAngle(pos.x, pos.y, ahead.x, ahead.y) + (arrowConfig.rotationOffset || 0);
    } else if (hasWaypoints) {
      const startPt = arrowConfig.waypoints[state2.segmentIndex];
      const endPt = arrowConfig.waypoints[state2.segmentIndex + 1];
      x = startPt.x + (endPt.x - startPt.x) * progress;
      y = startPt.y + (endPt.y - startPt.y) * progress;
      angle = getAngle(startPt.x, startPt.y, endPt.x, endPt.y) + (arrowConfig.rotationOffset || 0);
    } else {
      x = arrowConfig.start.x + (arrowConfig.end.x - arrowConfig.start.x) * progress;
      y = arrowConfig.start.y + (arrowConfig.end.y - arrowConfig.start.y) * progress;
      angle = getAngle(arrowConfig.start.x, arrowConfig.start.y, arrowConfig.end.x, arrowConfig.end.y) + (arrowConfig.rotationOffset || 0);
    }

    arrow.style.display = 'block';
    arrow.style.left = x + 'px';
    arrow.style.top = y + 'px';
    arrow.style.transform = `translate(-50%, -50%) rotate(${angle}deg)`;

    if (progress < 1) {
      animationFrames[arrowConfig.id] = requestAnimationFrame(step);
    } else {
      if (isCurve) {
        arrow.style.display = 'none';
        setTimeout(() => {
          state2.startTime = null;
          if (!state2.stopped) animationFrames[arrowConfig.id] = requestAnimationFrame(step);
        }, 500);
      } else if (hasWaypoints) {
        state2.segmentIndex++;
        if (state2.segmentIndex >= arrowConfig.waypoints.length - 1) {
          state2.segmentIndex = 0;
          arrow.style.display = 'none';
          setTimeout(() => {
            if (!state2.stopped) animateArrow(arrowConfig);
          }, 500);
        } else {
          state2.startTime = null;
          animationFrames[arrowConfig.id] = requestAnimationFrame(step);
        }
      } else {
        arrow.style.display = 'none';
        setTimeout(() => {
          arrow.style.left = arrowConfig.start.x + 'px';
          arrow.style.top = arrowConfig.start.y + 'px';
          if (!animationStates[arrowConfig.id].stopped) animateArrow(arrowConfig);
        }, 500);
      }
    }
  }

  animationStates[arrowConfig.id].stopped = false;
  animationFrames[arrowConfig.id] = requestAnimationFrame(step);
}

// หยุด animation ลูกศรทั้งหมด
function stopArrowsAnimation() {
  for (const id in animationFrames) {
    cancelAnimationFrame(animationFrames[id]);
    animationStates[id].stopped = true;
    const arrow = document.getElementById(id);
    if (arrow) {
      arrow.style.display = 'none';
    }
  }
}

// เริ่ม animation ลูกศรทั้งหมด
function startArrowsAnimation() {
  arrows.forEach(config => animateArrow(config));
}

// เริ่ม animation ตอนโหลดหน้า
startArrowsAnimation();


// ======================
// ตัวอย่างโค้ดปุ่มสร้าง PDF
// ======================
document.getElementById("create-pdf-btn").addEventListener("click", async () => {
  const pdfStatus = document.getElementById("pdf-status");
  const mapContent = document.getElementById("map-content");

  try {
    pdfStatus.textContent = "📸 กำลังเตรียมแผนที่...";

    // หยุด animation ลูกศรก่อนซ่อน
    stopArrowsAnimation();

    // ซ่อน overlay อื่น ๆ ถ้าต้องการ
    const overlaysToHide = document.querySelectorAll('.arrow-image, .mark-icon');
    overlaysToHide.forEach(el => {
      el.style.setProperty('display', 'none', 'important');
      el.style.setProperty('visibility', 'hidden', 'important');
    });

    // รอให้ browser render การซ่อนเสร็จ
    await new Promise(resolve => setTimeout(resolve, 100));

    // เปลี่ยนภาพพื้นหลัง (ถ้าต้องการ)
    const bgImage = document.querySelector('#map-content img');
    const originalSrc = bgImage?.src;

    if (bgImage) {
      await new Promise((resolve, reject) => {
        bgImage.onload = () => resolve();
        bgImage.onerror = () => reject("โหลดภาพพื้นหลังไม่สำเร็จ");
        bgImage.src = 'img4800/1.png';
      });
    }

    // ใช้ html2canvas จับภาพ
    const { jsPDF } = window.jspdf;
    const canvas = await html2canvas(mapContent, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    // สร้าง PDF
    const pdfWidth = 4500;
    const pdfHeight = 2658;

    const pdf = new jsPDF({
      orientation: "landscape",
      unit: "pt",
      format: [pdfWidth, pdfHeight],
    });

    pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
    pdfStatus.textContent = "✅ สร้าง PDF เสร็จแล้ว กำลังดาวน์โหลดไฟล์...";
    pdf.save("map-content.pdf");

    // คืนภาพพื้นหลังเดิม
    if (bgImage && originalSrc) {
      bgImage.src = originalSrc;
    }

    // คืนค่า overlay
    overlaysToHide.forEach(el => {
      el.style.removeProperty('display');
      el.style.removeProperty('visibility');
    });

    // เริ่ม animation ลูกศรใหม่
    startArrowsAnimation();

  } catch (error) {
    pdfStatus.textContent = "❌ เกิดข้อผิดพลาดในการสร้าง PDF";
    console.error(error);
  }
});
