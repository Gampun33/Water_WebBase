// Flatpickr config
flatpickr(".flatpickr", {
    dateFormat: "Y-m-d",
    maxDate: "today",
    locale: "th"
});

// ฟังก์ชันสำหรับดาวน์โหลด PDF
async function downloadPDF() {
    const content = document.getElementById("map-content");
    try {
        const canvas = await html2canvas(content, {
            scale: 3,
            useCORS: true
        });
        const imgData = canvas.toDataURL("image/jpeg", 1.0);
        const mmWidth = canvas.width * 0.264583;
        const mmHeight = canvas.height * 0.264583;
        const {
            jsPDF
        } = window.jspdf;
        const pdf = new jsPDF({
            orientation: mmWidth > mmHeight ? 'landscape' : 'portrait',
            unit: 'mm',
            format: [mmWidth, mmHeight]
        });
        pdf.addImage(imgData, 'JPEG', 0, 0, mmWidth, mmHeight);
        pdf.save("รายงานน้ำ.pdf");
    } catch (e) {
        alert('❌ สร้าง PDF ไม่สำเร็จ: ' + e);
    }
}

// ฟังก์ชันสร้าง GIF พร้อมอัปเดตข้อมูลแต่ละวัน
async function generateGIF() {
    const content = document.getElementById("map-content");
    const originalDateLabel = document.querySelector('.date-label').textContent;
    const gifButton = document.querySelector('button[onclick="generateGIF()"]');

    if (gifButton) {
        gifButton.disabled = true;
        gifButton.textContent = 'กำลังสร้าง GIF... 0%';
    }

    let currentDate = new Date('<?= $selectedDate ?>');

    const datesToCapture = [];
    for (let i = 0; i < 7; i++) {
        const date = new Date(currentDate);
        date.setDate(currentDate.getDate() - i);
        datesToCapture.push(date.toISOString().slice(0, 10));
    }
    datesToCapture.reverse();

    if (content.offsetWidth === 0 || content.offsetHeight === 0) {
        alert('ไม่สามารถสร้าง GIF ได้: เนื้อหาแผนที่ไม่มีขนาด (อาจยังโหลดไม่เสร็จสมบูรณ์)');
        if (gifButton) {
            gifButton.disabled = false;
            gifButton.textContent = '🖼️ สร้าง GIF';
        }
        return;
    }

    const backgroundImages = [
        '../img/Layer 1.png',
        '../img/Layer 2.png',
        '../img/Layer 3.png',
        '../img/Layer 4.png',
        '../img/Layer 5.png',
        '../img/Layer 6.png'
    ];

    const originalMapImage = document.querySelector('.map-image');
    if (originalMapImage) {
        originalMapImage.style.display = 'none';
    }

    const gif = new GIF({
        workers: 2,
        quality: 10,
        width: content.offsetWidth,
        height: content.offsetHeight,
        workerScript: 'assets/gif.worker.js'
    });

    for (let i = 0; i < datesToCapture.length; i++) {
        const date = datesToCapture[i];

        if (gifButton) {
            const progress = Math.round(((i + 1) / datesToCapture.length) * 100);
            gifButton.textContent = `กำลังสร้าง GIF... ${progress}%`;
        }

        try {
            const response = await fetch(`get_map_data.php?date=${date}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const newData = await response.json();
            updateMapContent(newData);
        } catch (e) {
            console.error("❌ Error fetching or updating data for date " + date + ":", e);
            alert(`ไม่สามารถโหลดข้อมูลสำหรับวันที่ ${formatThaiDateForGIF(date)} ได้: ${e.message}`);
            continue;
        }

        document.querySelector('.date-label').textContent = `ข้อมูล ณ วันที่ ${formatThaiDateForGIF(date)} เวลา 6.00 น.`;

        let currentBgImage = null;
        if (backgroundImages[i]) {
            currentBgImage = document.createElement('img');
            currentBgImage.src = backgroundImages[i];
            currentBgImage.alt = 'Background';
            currentBgImage.style.position = 'absolute';
            currentBgImage.style.top = '0';
            currentBgImage.style.left = '0';
            currentBgImage.style.width = '100%';
            currentBgImage.style.height = '100%';
            currentBgImage.style.zIndex = '0';
            currentBgImage.style.objectFit = 'cover';

            await new Promise(resolve => {
                currentBgImage.onload = resolve;
                currentBgImage.onerror = () => {
                    console.error(`❌ Error loading background image: ${backgroundImages[i]}`);
                    resolve();
                };
            });
            content.prepend(currentBgImage);
        } else {
            console.warn(`⚠️ ไม่พบรูปพื้นหลังสำหรับเฟรมที่ ${i + 1}`);
        }

        await new Promise(resolve => setTimeout(resolve, 500));

        try {
            const canvas = await html2canvas(content, {
                scale: 1,
                useCORS: true,
                backgroundColor: null
            });

            gif.addFrame(canvas, {
                delay: 800,
                copy: true
            });

        } catch (e) {
            console.error("❌ Error capturing frame for " + date + ":", e);
            alert('❌ เกิดข้อผิดพลาดในการสร้างเฟรม: ' + e.message);
            break;
        } finally {
            if (currentBgImage && currentBgImage.parentNode === content) {
                content.removeChild(currentBgImage);
            }
        }
    }

    document.querySelector('.date-label').textContent = originalDateLabel;

    if (originalMapImage) {
        originalMapImage.style.display = '';
    }

    gif.on('finished', function (blob) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'รายงานน้ำ_7วัน.gif';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        alert('✅ สร้าง GIF สำเร็จ!');
        if (gifButton) {
            gifButton.disabled = false;
            gifButton.textContent = '🖼️ สร้าง GIF';
        }
    });

    alert('🚀 กำลังเรนเดอร์ GIF... โปรดรอสักครู่');
    gif.render();
}

// ฟังก์ชันสำหรับอัปเดต DOM ด้วยข้อมูลใหม่
function updateMapContent(newData) {
    // --- 1. อัปเดตข้อมูลสถานี (Markers) ---
    newData.stations.forEach(newStation => {
        const markerElement = document.querySelector(`.marker[data-station-id="${newStation.station_id}"]`);
        if (markerElement) {
            markerElement.querySelector('span:nth-child(1)').textContent = formatFloatAtLeastTwoDecimals(newStation.current_water);
            markerElement.querySelector('span:nth-child(2)').textContent = `(${newStation.capacity}%)`;
            markerElement.querySelector('span:nth-child(3)').textContent = `${formatFloatAtLeastTwoDecimals(newStation.inflow)}/`;
            markerElement.querySelector('span:nth-child(4)').textContent = `${formatFloatAtLeastTwoDecimals(newStation.outflow)}`;
        }
    });

    // --- 2. อัปเดตข้อมูลสรุป (Counts) ---
    for (const key in newData.counts) {
        const countElement = document.getElementById(`count-${key}`);
        if (countElement) {
            countElement.textContent = newData.counts[key];
        }
    }

    // --- 3. อัปเดตข้อมูลฝน (Rain Data) ---
    newData.displayData.forEach(newRainData => {
        const rainRowElement = document.querySelector(`.rain-row[data-rain-location="${newRainData.location}"]`);
        if (rainRowElement) {
            const rainfallValue = newRainData.rainfall_24h;
            const rainfallBox = rainRowElement.querySelector('.rainfall-box');
            rainfallBox.textContent = formatFloatAtLeastoneDecimals(rainfallValue);
            rainfallBox.classList.remove('no-rain', 'light-rain', 'moderate-rain', 'heavy-rain', 'very-heavy-rain');
            rainfallBox.classList.add(getRainfallClass(rainfallValue));
            rainRowElement.querySelector('.rain-72h').textContent = formatFloatAtLeastoneDecimals(newRainData.rainfall_72h);
        }
    });

    // --- 4. อัปเดตข้อมูลแท็งก์น้ำ (Tanks) ---
    newData.tanks.forEach(newTank => {
        const tankItem = document.querySelector(`.tank-item[data-tank-id="${newTank.id}"]`);
        if (tankItem) {
            const current = newTank.water_current;
            const capacity = newTank.water_capacity;
            const percent = (capacity > 0) ? (current / capacity * 100) : 0;

            const maxHeight = 103;
            let height = (percent / 100) * maxHeight;
            height = (percent > 0 && height < 2) ? 2 : height;
            const y = maxHeight - height;

            const text_color = percent > 100 ? 'white' : 'black';
            const water_color = getWaterColor(percent);

            const waterRect = tankItem.querySelector('.water-rect');
            if (waterRect) {
                waterRect.setAttribute('y', y);
                waterRect.setAttribute('height', height);
                waterRect.setAttribute('fill', water_color);
            }

            const currentWaterText = tankItem.querySelector('.current-water-text');
            if (currentWaterText) {
                currentWaterText.textContent = formatDecimal1or2(current);
                currentWaterText.setAttribute('fill', text_color);
                const oldPercentTspan = currentWaterText.querySelector('.percent-text');
                if (oldPercentTspan) {
                    currentWaterText.removeChild(oldPercentTspan);
                }
                const percentTspan = document.createElement('tspan');
                percentTspan.setAttribute('font-size', '18');
                percentTspan.classList.add('percent-text');
                percentTspan.textContent = `(${number_format(percent, 0)}%)`;
                currentWaterText.appendChild(percentTspan);
            }

            const waterLevelText = tankItem.querySelector('.water-level-text');
            if (waterLevelText) {
                waterLevelText.textContent = number_format(parseFloat(newTank.water_level), 2);
            }

            const topLabel = tankItem.querySelector('.top-label');
            if (topLabel) {
                topLabel.textContent = `${number_format(capacity, 2)} cms`;
            }
        }
    });
}

// ฟังก์ชันช่วยจัดรูปแบบตัวเลข (นำมาจาก PHP functions)
function formatFloatAtLeastTwoDecimals(num) {
    if (num === null || num === undefined) return 'N/A';
    return parseFloat(num).toFixed(2);
}

function formatFloatAtLeastoneDecimals(num) {
    if (num === null || num === undefined) return 'N/A';
    return parseFloat(num).toFixed(1);
}

function formatDecimal1or2(num) {
    if (num === null || num === undefined) return 'N/A';
    const floatNum = parseFloat(num);
    if (Number.isInteger(floatNum)) {
        return floatNum.toFixed(1);
    }
    return floatNum.toFixed(2);
}

// ฟังก์ชันช่วยกำหนดสีน้ำ (นำมาจาก PHP functions)
function getWaterColor(percent) {
    if (percent > 100) return '#FF0000';
    if (percent > 80) return '#3399FF';
    if (percent > 60) return '#66CCFF';
    if (percent > 40) return '#99CCFF';
    if (percent > 20) return '#CCEEFF';
    return '#DDDDDD';
}

// ฟังก์ชันช่วยกำหนดคลาสฝน (นำมาจาก PHP functions)
function getRainfallClass(value) {
    if (value === null || value === undefined || value <= 0) return 'no-rain';
    if (value > 0 && value <= 35) return 'light-rain';
    if (value > 35 && value <= 90) return 'moderate-rain';
    if (value > 90 && value <= 150) return 'heavy-rain';
    return 'very-heavy-rain';
}

// ฟังก์ชันสำหรับจัดรูปแบบวันที่แบบไทยสำหรับแสดงใน GIF
function formatThaiDateForGIF(dateString) {
    const date = new Date(dateString);
    const thaiMonths = [
        "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
        "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    ];
    const day = date.getDate();
    const month = thaiMonths[date.getMonth() + 1];
    const year = date.getFullYear() + 543;
    return `${day} ${month} ${year}`;
}

// เนื่องจาก PHP number_format ไม่มีใน JS, สร้างฟังก์ชันที่คล้ายกัน
function number_format(number, decimals = 0) {
    const fixed = parseFloat(number).toFixed(decimals);
    return fixed.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function scaleMap() {
    const map = document.getElementById('map-content');
    const wrapper = document.querySelector('.map-wrapper');
    const scaleX = wrapper.clientWidth / 4500;
    const scaleY = wrapper.clientHeight / 2658;
    const scale = Math.min(scaleX, scaleY);

    map.style.transform = `scale(${scale})`;
}

window.addEventListener('load', scaleMap);
window.addEventListener('resize', scaleMap);
