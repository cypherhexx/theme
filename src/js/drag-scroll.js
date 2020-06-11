// --------------------------------------------------------------------------------------------------
// Drag to Scroll
// --------------------------------------------------------------------------------------------------

const slider = document.querySelector('.drag-scroll');
let isDown = false;
let startX;
let scrollLeft;

if(slider) {
  slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('drag-active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });
  slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('drag-active');
  });
  slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('drag-active');
  });
  slider.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1; //scroll-fast
    slider.scrollLeft = scrollLeft - walk;
    //console.log(walk);
  });
}