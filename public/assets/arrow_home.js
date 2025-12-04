const arrows = [
  // ตัวอย่าง config ลูกศรเหมือนเดิม
  {
    id: 'arrow-1',
    type: 'curve',
    curve: {
      p0: { x: 970, y: 850 },
      cp: { x: 1050, y: 850 },
      p1: { x: 1051, y: 885 }
    },
    duration: 5000,
    rotationOffset: 0,
  },
  {
    id: 'arrow-2',
    type: 'combo',
    rotationOffset: 0,
    segments: [
      {
        type: 'line',
        start: { x: 1107, y: 100 },
        end: { x: 1107, y: 860 },
        duration: 13000
      },
      {
        type: 'line',
        start: { x: 1090, y: 875 },
        end: { x: 1080, y: 875 },
        duration: 700
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
        start: { x: 968, y: 155 },
        end: { x: 968, y: 258 },
        duration: 4000
      },
      {
        type: 'line',
        start: { x: 990, y: 272 },
        end: { x: 1090, y: 272 },
        duration: 4000
      }
    ]
  },
  {
    id: 'arrow-4',
    type: 'line',
    start: { x: 1370, y: 245 },
    end: { x: 1125, y: 245 },
    duration: 7000,
    rotationOffset: 0
  },
  {
    id: 'arrow-5',
    type: 'line',
    start: { x: 1153, y: 282 },
    end: { x: 1125, y: 282 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-6',
    type: 'line',
    start: { x: 1390, y: 618 },
    end: { x: 1125, y: 618 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-7',
    type: 'line',
    start: { x: 1320, y: 667 },
    end: { x: 1125, y: 667 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-8',
    type: 'line',
    start: { x: 1350, y: 681 },
    end: { x: 1125, y: 681 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-9',
    type: 'line',
    start: { x: 775, y: 757 },
    end: { x: 1100, y: 757 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-10',
    type: 'line',
    start: { x: 910, y: 721 },
    end: { x: 1100, y: 721 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-11',
    type: 'line',
    start: { x: 995, y: 789 },
    end: { x: 1100, y: 789 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-12',
    type: 'line',
    start: { x: 780, y: 630 },
    end: { x: 900, y: 630 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-13',
    type: 'line',
    start: { x: 810, y: 609 },
    end: { x: 900, y: 609 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-14',
    type: 'line',
    start: { x: 760, y: 489 },
    end: { x: 1100, y: 489 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-15',
    type: 'line',
    start: { x: 765, y: 410 },
    end: { x: 940, y: 410 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-16',
    type: 'line',
    start: { x: 780, y: 386 },
    end: { x: 940, y: 386 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-17',
    type: 'line',
    start: { x: 790, y: 362 },
    end: { x: 940, y: 362 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-18',
    type: 'line',
    start: { x: 810, y: 339 },
    end: { x: 940, y: 339 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-19',
    type: 'line',
    start: { x: 815, y: 316 },
    end: { x: 940, y: 316 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-20',
    type: 'line',
    start: { x: 840, y: 256 },
    end: { x: 960, y: 256 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-21',
    type: 'line',
    start: { x: 1281, y: 465 },
    end: { x: 1281, y: 510 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-22',
    type: 'line',
    start: { x: 1315, y: 400 },
    end: { x: 1315, y: 510 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-23',
    type: 'line',
    start: { x: 1160, y: 546 },
    end: { x: 1125, y: 546 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-24',
    type: 'line',
    start: { x: 1220, y: 540 },
    end: { x: 1220, y: 530 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-25',
    type: 'line',
    start: { x: 1270, y: 590 },
    end: { x: 1270, y: 530 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-26',
    type: 'line',
    start: { x: 1239, y: 695 },
    end: { x: 1239, y: 688 },
    duration: 3500,
    rotationOffset: 0
  },
  {
    id: 'arrow-27',
    type: 'line',
    start: { x: 1210, y: 700 },
    end: { x: 1210, y: 690 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-28',
    type: 'line',
    start: { x: 933, y: 540 },
    end: { x: 933, y: 570 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-29',
    type: 'line',
    start: { x: 1010, y: 400 },
    end: { x: 1090, y: 400 },
    duration: 5000,
    rotationOffset: 0
  },
  {
    id: 'arrow-30',
    type: 'line',
    start: { x: 1030, y: 380 },
    end: { x: 1090, y: 380 },
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
        start: { x: 1012, y: 160 },
        end: { x: 1012, y: 205 },
        duration: 2500
      },
      {
        type: 'line',
        start: { x: 1000, y: 225 },
        end: { x: 980, y: 225 },
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
        start: { x: 820, y: 295 },
        end: { x: 950, y: 295 },
        duration: 2500
      },
      {
        type: 'line',
        start: { x: 955, y: 310 },
        end: { x: 955, y: 440 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 970, y: 450 },
        end: { x: 1090, y: 450 },
        duration: 2000
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
        start: { x: 725, y: 422 },
        end: { x: 920, y: 422 },
        duration: 4000
      },
      {
        type: 'line',
        start: { x: 940, y: 435 },
        end: { x: 940, y: 460 },
        duration: 1500
      },
      {
        type: 'line',
        start: { x: 960, y: 470 },
        end: { x: 1050, y: 470 },
        duration: 1500
      },
      {
        type: 'line',
        start: { x: 1072, y: 478 },
        end: { x: 1072, y: 482 },
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
        start: { x: 1346, y: 300 },
        end: { x: 1346, y: 330 },
        duration: 1500
      },
      {
        type: 'line',
        start: { x: 1330, y: 342 },
        end: { x: 1120, y: 342 },
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
        start: { x: 1423, y: 390 },
        end: { x: 1423, y: 500 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 1415, y: 521 },
        end: { x: 1120, y: 521 },
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
        start: { x: 910, y: 680 },
        end: { x: 910, y: 600 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 920, y: 583 },
        end: { x: 1090, y: 583 },
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
        start: { x: 770, y: 437 },
        end: { x: 870, y: 437 },
        duration: 2000
      },
      {
        type: 'line',
        start: { x: 882, y: 447 },
        end: { x: 882, y: 480 },
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
        start: { x: 800, y: 467 },
        end: { x: 845, y: 467 },
        duration: 2500
      },
      {
        type: 'line',
        start: { x: 857, y: 477 },
        end: { x: 857, y: 483 },
        duration: 1000
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
        start: { x: 810, y: 511 },
        end: { x: 860, y: 511 },
        duration: 3000
      },
      {
        type: 'line',
        start: { x: 874, y: 495 },
        end: { x: 874, y: 494 },
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
