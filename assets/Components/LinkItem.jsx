function LinkItem({ path, title }) {
  return (
    <span className='whitespace-no-wrap'>
      {' '}
      /{' '}
      <a href={path} className='no-underline'>
        {title}
      </a>
    </span>
  )
}

export { LinkItem }
