const today = new Date().toISOString().split('T')[0];
console.log(today);
document.getElementById('date').setAttribute('max', today);