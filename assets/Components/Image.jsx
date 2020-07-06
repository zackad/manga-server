import lozad from 'lozad'
import { useEffect } from 'react'

function Image({ src, alt, borderImage }) {
  const observer = lozad('.lozad', {
    loaded: (el) => {
      setTimeout(() => {
        el.classList.remove('min-h-screen')
      }, 100)
    },
  })

  useEffect(() => {
    observer.observe()
  }, [])

  return (
    <div className={borderImage ? `border-t` : ``}>
      <img className='mx-auto min-h-screen lozad' data-src={src} alt={alt} />
    </div>
  )
}

export { Image }
