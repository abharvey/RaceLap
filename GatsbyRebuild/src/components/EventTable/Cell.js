import React from "react"
import styled from "styled-components"

const Cell = styled.td`
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;

  .place-cell {
    display: inline-block;
    vertical-align: middle;
    text-align: center;
    width: 50px;
  }

  .name-cell {
    min-width: 180px;
    max-width: 180px;
    text-align: left;
  }

  .club-cell {
    min-width: 180px;
    max-width: 180px;
    text-align: center;
  }

  .laps-cell {
    min-width: 40px;
  }
`

const replaceColWhiteSpace = col => col.toLowerCase().replace(/[\s]+/g, "-")

export default ({ column, className, children, ...rest }) => (
  <Cell
    {...rest}
    className={`${className} ${replaceColWhiteSpace(column)}-cell`}
  >
    {children}
  </Cell>
)
