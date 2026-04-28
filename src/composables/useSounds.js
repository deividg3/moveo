


let ctx = null

function getCtx() {
  if (!ctx) {
    ctx = new (window.AudioContext || window.webkitAudioContext)()
  }
  
  if (ctx.state === 'suspended') {
    ctx.resume()
  }
  return ctx
}

function playTone({ freq, type = 'sine', duration = 0.12, gain = 0.35, delay = 0 }) {
  const ac = getCtx()
  const osc = ac.createOscillator()
  const env = ac.createGain()

  osc.type = type
  osc.frequency.setValueAtTime(freq, ac.currentTime + delay)

  env.gain.setValueAtTime(0, ac.currentTime + delay)
  env.gain.linearRampToValueAtTime(gain, ac.currentTime + delay + 0.01)
  env.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + delay + duration)

  osc.connect(env)
  env.connect(ac.destination)

  osc.start(ac.currentTime + delay)
  osc.stop(ac.currentTime + delay + duration + 0.02)
}

export function useSounds() {
  
  function playCorrect() {
    try {
      playTone({ freq: 523.25, type: 'triangle', duration: 0.10, gain: 0.28, delay: 0.00 })
      playTone({ freq: 659.25, type: 'triangle', duration: 0.10, gain: 0.28, delay: 0.09 })
      playTone({ freq: 783.99, type: 'triangle', duration: 0.18, gain: 0.32, delay: 0.18 })
      
      playTone({ freq: 1046.5, type: 'sine',     duration: 0.22, gain: 0.10, delay: 0.18 })
    } catch {
      
    }
  }

  
  function playWrong() {
    try {
      playTone({ freq: 220,    type: 'sawtooth', duration: 0.14, gain: 0.25, delay: 0.00 })
      playTone({ freq: 493.88, type: 'square',   duration: 0.06, gain: 0.07, delay: 0.00 })
      playTone({ freq: 185,    type: 'sawtooth', duration: 0.20, gain: 0.20, delay: 0.13 })
    } catch {
      
    }
  }

  return { playCorrect, playWrong }
}
