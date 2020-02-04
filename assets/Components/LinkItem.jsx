import { h } from 'preact'

function LinkItem({ path, title }) {
  return (
    <span>
      {' '}
      /{' '}
      <a href={path} className='no-underline'>
        {title}
      </a>
    </span>
  )
}

export { LinkItem }
