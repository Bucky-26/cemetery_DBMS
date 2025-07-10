<?php 
include 'conn.php';

                                $query = "SELECT * FROM burial_only_record   ORDER BY burial_date DESC";
                                $result = $conn->query($query);

                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>
                                            <div class='d-flex justify-content-center px-2 py-1'>
                                                <div class='d-flex flex-column justify-content-center'>
                                                    <h6 class='mb-0 text-sm'>".$row['burial_id']."</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><p class='text-xs font-weight-bold mb-0 text-center'>".$row['plot_title']."</p></td>
                                        <td><p class='text-xs font-weight-bold mb-0 text-center'>".$row['burial_name']."</p></td>
                                        <td><p class='text-xs font-weight-bold mb-0 text-center'>".$row['burial_date']."</p></td>
                                        <td><p class='text-xs font-weight-bold mb-0 text-center'>".$row['remarks']."</p></td>
                                        <td class='text-center'>
                                            <button type='button' class='btn btn-primary btn-sm' onclick='editsDecease  (".$row['burial_id'].")'><i class='fas fa-edit'></i></button>
                                                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRecord(".$row['burial_id'].")'><i class='fas fa-trash'></i></button>
                                            <button type='button' class='btn btn-info btn-sm' onclick='viewDecease(".$row['burial_id'].")'><i class='fas fa-eye'></i></button>
                                        </td>
                                    </tr>";
                                }
                 ?>