function addHeadline() {
  const container = document.getElementById('headlines-container');
  const index = container.children.length;
  const headline = `
    <div class="headline">
      <label for="headline-title-${index}">Headline Title:</label>
      <input type="text" id="headline-title-${index}" name="headline_titles[]" required><br>

      <label for="headline-subtitle-${index}">Headline Subtitle:</label>
      <input type="text" id="headline-subtitle-${index}" name="headline_subtitles[]" required><br>

      <label for="headline-paragraph-${index}">Headline Paragraph:</label>
      <textarea id="headline-paragraph-${index}" name="headline_paragraphs[]" required></textarea><br>

      <label for="headline-image-${index}">Headline Image:</label>
      <input type="file" id="headline-image-${index}" name="headline_images[]"><br>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', headline);
}
