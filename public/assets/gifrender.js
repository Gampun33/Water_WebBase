document.getElementById("record-pdf-btn").addEventListener("click", async () => {
  const statusEl = document.getElementById("gif-status");
  const mapWrapper = document.getElementById("map-wrapper");

  statusEl.textContent = "⏳ กำลังจับภาพ...";

  const scaleFactor = 4;

  // ✅ ปิดเอฟเฟกต์ระหว่างจับภาพ
  mapWrapper.classList.add("no-shadow");

  // รอ DOM อัปเดต
  await new Promise(r => setTimeout(r, 300));

  // จับภาพเฟรมเดียว
  const canvas = await html2canvas(mapWrapper, {
    scale: scaleFactor,
    useCORS: true,
    backgroundColor: "#ffffff"
  });

  const dataUrl = canvas.toDataURL("image/png");
  const width = mapWrapper.clientWidth * scaleFactor;
  const height = mapWrapper.clientHeight * scaleFactor;

  mapWrapper.classList.remove("no-shadow");

  statusEl.textContent = "📄 กำลังสร้าง PDF...";

  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF({
    orientation: "landscape",
    unit: "px",
    format: [width, height]
  });

  pdf.addImage(dataUrl, "PNG", 0, 0, width, height);

  const today = new Date().toISOString().split("T")[0];
  pdf.save(`snapshot-${today}.pdf`);

  statusEl.textContent = "✅ บันทึก PDF เสร็จเรียบร้อย!";
});
